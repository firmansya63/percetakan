<?php

namespace App\Services;

use App\Models\CashFlow;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Laporan Keuangan Global (Konsolidasi).
 *
 * Menggabungkan pemasukan (kasir), HPP bahan baku (produksi),
 * pengeluaran operasional manual (owner/kasir), serta piutang usaha.
 *
 * Struktur dibuat modular agar mudah diintegrasikan dengan modul
 * payroll di masa mendatang (lihat `pengeluaranOperasional()`).
 */
class LaporanGlobalService
{
    public Carbon $start;

    public Carbon $end;

    /**
     * Filter kategori produk (jenis cetakan), opsional.
     */
    public ?string $kategoriProduk = null;

    /**
     * @param  string  $startDate  Format Y-m-d
     * @param  string  $endDate  Format Y-m-d
     */
    public function __construct(string $startDate, string $endDate, ?string $kategoriProduk = null)
    {
        $this->start = Carbon::parse($startDate)->startOfDay();
        $this->end = Carbon::parse($endDate)->endOfDay();
        $this->kategoriProduk = $kategoriProduk ?: null;
    }

    /**
     * ID pesanan yang termasuk dalam filter kategori produk (jika ada).
     */
    private function pesananIdsFiltered()
    {
        if (! $this->kategoriProduk) {
            return null;
        }

        return DetailPesanan::query()
            ->whereHas('bahan', fn ($q) => $q->where('jenis_cetakan', $this->kategoriProduk))
            ->pluck('pesanan_id')
            ->unique();
    }

    /**
     * Ringkasan KPI utama untuk kartu ringkasan.
     */
    public function summary(): array
    {
        $pemasukan = $this->pemasukan();
        $pengeluaran = $this->pengeluaranOperasional();
        $hpp = $this->hpp();
        $piutang = $this->piutang();

        $totalOmzetKotor = $pemasukan['total_omzet'];
        $kasMasukRiil = $pemasukan['kas_masuk'];
        $totalBeban = $hpp['total'] + $pengeluaran['total'];
        $labaBersih = $kasMasukRiil - $totalBeban;

        return [
            'total_omzet_kotor' => $totalOmzetKotor,
            'kas_masuk_riil' => $kasMasukRiil,
            'total_beban' => $totalBeban,
            'hpp' => $hpp['total'],
            'opex' => $pengeluaran['total'],
            'laba_bersih' => $labaBersih,
            'total_piutang' => $piutang['total'],
        ];
    }

    /**
     * Pemasukan (revenue) dari modul kasir.
     *
     * - total_omzet : nilai penjualan (pesanan) dalam rentang.
     * - kas_masuk   : uang yang benar-benar diterima (pembayaran masuk).
     * - per_metode  : rincian kas masuk per metode pembayaran.
     */
    public function pemasukan(): array
    {
        $pesananIds = $this->pesananIdsFiltered();

        // Nilai penjualan (omzet kotor) berdasarkan pesanan yang tercatat.
        $omzetQuery = Pesanan::whereBetween('tanggal_pesanan', [$this->start, $this->end]);
        if ($pesananIds !== null) {
            $omzetQuery->whereIn('id', $pesananIds);
        }
        $totalOmzet = (float) (clone $omzetQuery)->sum('total_harga');

        // Uang yang benar-benar diterima (pembayaran DP + pelunasan).
        $pembayaranQuery = Pembayaran::whereBetween('created_at', [$this->start, $this->end]);
        if ($pesananIds !== null) {
            $pembayaranQuery->whereIn('pesanan_id', $pesananIds);
        }

        $kasMasuk = (float) (clone $pembayaranQuery)->sum('jumlah');

        $perMetode = (clone $pembayaranQuery)
            ->selectRaw('metode_pembayaran as metode, SUM(jumlah) as total')
            ->groupBy('metode_pembayaran')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->metode => (float) $row->total]);

        // Rincian DP vs Pelunasan.
        $dp = (float) (clone $pembayaranQuery)->where('tipe', 'dp')->sum('jumlah');
        $pelunasan = (float) (clone $pembayaranQuery)->where('tipe', 'pelunasan')->sum('jumlah');

        return [
            'total_omzet' => $totalOmzet,
            'kas_masuk' => $kasMasuk,
            'dp' => $dp,
            'pelunasan' => $pelunasan,
            'per_metode' => [
                'tunai' => $perMetode->get('tunai', 0.0),
                'transfer' => $perMetode->get('transfer', 0.0),
                'qris' => $perMetode->get('qris', 0.0),
            ],
        ];
    }

    /**
     * Harga Pokok Produksi (HPP) — pemakaian bahan baku.
     *
     * Dihitung dari detail pesanan (produksi) menggunakan harga dasar
     * bahan (`harga_dasar`), dikelompokkan per jenis cetakan.
     *
     * TODO: saat modul stok/work-order tersedia, ganti sumber data
     * menjadi tabel konsumsi bahan yang lebih presisi.
     */
    public function hpp(): array
    {
        $rowsQuery = DetailPesanan::with('bahan')
            ->whereHas('pesanan', function ($q) {
                $q->whereBetween('tanggal_pesanan', [$this->start, $this->end]);
            })
            ->where('is_jasa_design', false);

        if ($this->kategoriProduk) {
            $rowsQuery->whereHas('bahan', fn ($q) => $q->where('jenis_cetakan', $this->kategoriProduk));
        }

        $rows = $rowsQuery->get();

        $total = 0.0;
        $perKategori = [];

        foreach ($rows as $row) {
            $bahan = $row->bahan;
            if (! $bahan) {
                continue;
            }

            $hargaDasar = (float) $bahan->harga_dasar;
            $kategori = $bahan->jenis_cetakan ?? 'lainnya';

            // Konsumsi: banner pakai luas, lainnya pakai qty.
            if ($bahan->jenis_cetakan === 'banner' && $row->panjang && $row->lebar) {
                $konsumsi = (float) $row->panjang * (float) $row->lebar * (int) $row->qty;
            } else {
                $konsumsi = (int) $row->qty;
            }

            $hppItem = $konsumsi * $hargaDasar;
            $total += $hppItem;

            $perKategori[$kategori] = ($perKategori[$kategori] ?? 0.0) + $hppItem;
        }

        return [
            'total' => $total,
            'per_kategori' => $perKategori,
        ];
    }

    /**
     * Pengeluaran Operasional (OpEx) — input manual cashflow tipe keluar.
     *
     * Termasuk pos gaji & lembur yang saat ini dicatat manual oleh owner
     * (modul payroll belum tersedia). Dikelompokkan per kategori agar
     * tabel laba rugi dapat menampilkan rincian beban.
     */
    public function pengeluaranOperasional(): array
    {
        $rows = CashFlow::where('tipe', 'keluar')
            ->whereBetween('tanggal', [$this->start->toDateString(), $this->end->toDateString()])
            ->get();

        $total = 0.0;
        $perKategori = [];

        foreach ($rows as $row) {
            $jumlah = (float) $row->jumlah;
            $kategori = $row->kategori ?: 'Lain-lain';
            $total += $jumlah;
            $perKategori[$kategori] = ($perKategori[$kategori] ?? 0.0) + $jumlah;
        }

        // Urutkan kategori berdasar total terbesar.
        arsort($perKategori);

        return [
            'total' => $total,
            'per_kategori' => $perKategori,
            'rows' => $rows,
        ];
    }

    /**
     * Piutang usaha (belum lunas / baru bayar DP).
     */
    public function piutang(): array
    {
        $rows = Pesanan::with('pelanggan')
            ->where('status_pembayaran', 'belum_lunas')
            ->where('sisa_bayar', '>', 0)
            ->whereBetween('tanggal_pesanan', [$this->start, $this->end])
            ->orderBy('tanggal_pesanan', 'desc')
            ->get();

        return [
            'total' => (float) $rows->sum('sisa_bayar'),
            'rows' => $rows,
        ];
    }

    /**
     * Data tren pemasukan vs pengeluaran (harian) untuk grafik.
     */
    public function tren(): Collection
    {
        $mulai = $this->start->copy();
        $selesai = $this->end->copy();

        // Bangun kerangka tanggal lengkap (array biasa agar bisa diubah in-place).
        $dates = [];
        for ($d = $mulai->copy(); $d->lte($selesai); $d->addDay()) {
            $dates[$d->format('Y-m-d')] = [
                'tanggal' => $d->format('Y-m-d'),
                'label' => $d->format('d M'),
                'masuk' => 0.0,
                'keluar' => 0.0,
            ];
        }

        $masuk = Pembayaran::whereBetween('created_at', [$this->start, $this->end])
            ->selectRaw('DATE(created_at) as tgl, SUM(jumlah) as total')
            ->groupBy('tgl')
            ->pluck('total', 'tgl');

        $keluar = CashFlow::where('tipe', 'keluar')
            ->whereBetween('tanggal', [$this->start->toDateString(), $this->end->toDateString()])
            ->selectRaw('tanggal as tgl, SUM(jumlah) as total')
            ->groupBy('tgl')
            ->pluck('total', 'tgl');

        foreach ($masuk as $tgl => $total) {
            if (array_key_exists($tgl, $dates)) {
                $dates[$tgl]['masuk'] = (float) $total;
            }
        }
        foreach ($keluar as $tgl => $total) {
            if (array_key_exists($tgl, $dates)) {
                $dates[$tgl]['keluar'] = (float) $total;
            }
        }

        return collect(array_values($dates));
    }

    /**
     * Rekonsiliasi kas & bank: saldo per metode pembayaran.
     */
    public function rekonsiliasi(): array
    {
        $perMetode = Pembayaran::whereBetween('created_at', [$this->start, $this->end])
            ->selectRaw('metode_pembayaran as metode, SUM(jumlah) as total')
            ->groupBy('metode_pembayaran')
            ->pluck('total', 'metode');

        $pengeluaranTunai = (float) CashFlow::where('tipe', 'keluar')
            ->where('metode_pembayaran', 'tunai')
            ->whereBetween('tanggal', [$this->start->toDateString(), $this->end->toDateString()])
            ->sum('jumlah');

        $pengeluaranTransfer = (float) CashFlow::where('tipe', 'keluar')
            ->where('metode_pembayaran', 'transfer')
            ->whereBetween('tanggal', [$this->start->toDateString(), $this->end->toDateString()])
            ->sum('jumlah');

        $pengeluaranQris = (float) CashFlow::where('tipe', 'keluar')
            ->where('metode_pembayaran', 'qris')
            ->whereBetween('tanggal', [$this->start->toDateString(), $this->end->toDateString()])
            ->sum('jumlah');

        return [
            'tunai' => [
                'masuk' => (float) ($perMetode['tunai'] ?? 0),
                'keluar' => $pengeluaranTunai,
                'saldo' => (float) ($perMetode['tunai'] ?? 0) - $pengeluaranTunai,
            ],
            'transfer' => [
                'masuk' => (float) ($perMetode['transfer'] ?? 0),
                'keluar' => $pengeluaranTransfer,
                'saldo' => (float) ($perMetode['transfer'] ?? 0) - $pengeluaranTransfer,
            ],
            'qris' => [
                'masuk' => (float) ($perMetode['qris'] ?? 0),
                'keluar' => $pengeluaranQris,
                'saldo' => (float) ($perMetode['qris'] ?? 0) - $pengeluaranQris,
            ],
        ];
    }
}
