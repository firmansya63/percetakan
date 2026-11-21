<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\LaporanGlobalService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanGlobalController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_laporan_global', only: ['index', 'exportCsv']),
            new Middleware('permission:view_setoran_harian', only: ['setoran']),
        ];
    }

    /**
     * Laporan keuangan global (konsolidasi) — khusus Owner.
     */
    public function index(Request $request): View
    {
        [$startDate, $endDate] = $this->resolveRange($request);
        $kategoriProduk = $request->get('kategori_produk');

        $service = new LaporanGlobalService($startDate, $endDate, $kategoriProduk);

        $summary = $service->summary();
        $pemasukan = $service->pemasukan();
        $hpp = $service->hpp();
        $opex = $service->pengeluaranOperasional();
        $piutang = $service->piutang();
        $tren = $service->tren();
        $rekonsiliasi = $service->rekonsiliasi();

        // Kategori produk (jenis cetakan) untuk filter.
        $kategoriProduk = $this->kategoriProduk();

        return view('laporan.global', compact(
            'summary',
            'pemasukan',
            'hpp',
            'opex',
            'piutang',
            'tren',
            'rekonsiliasi',
            'kategoriProduk',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Ringkasan setoran harian per kasir (kasir hanya lihat milik sendiri).
     */
    public function setoran(Request $request): View
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        $start = \Carbon\Carbon::parse($tanggal)->startOfDay();
        $end = \Carbon\Carbon::parse($tanggal)->endOfDay();

        $user = $request->user();

        $pembayarans = Pembayaran::with('pesanan.pelanggan')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        $perMetode = $pembayarans->groupBy('metode_pembayaran')
            ->map(fn ($items) => $items->sum('jumlah'));

        return view('laporan.setoran', compact('tanggal', 'pembayarans', 'perMetode'));
    }

    /**
     * Export CSV (dibuka dengan Excel).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        [$startDate, $endDate] = $this->resolveRange($request);
        $kategoriProduk = $request->get('kategori_produk');

        $service = new LaporanGlobalService($startDate, $endDate, $kategoriProduk);

        $summary = $service->summary();
        $pemasukan = $service->pemasukan();
        $hpp = $service->hpp();
        $opex = $service->pengeluaranOperasional();
        $rekonsiliasi = $service->rekonsiliasi();

        $filename = 'laporan-global-'.$startDate.'_'.$endDate.'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return response()->streamDownload(function () use ($summary, $pemasukan, $hpp, $opex, $rekonsiliasi, $startDate, $endDate) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8.

            fputcsv($out, ['LAPORAN KEUANGAN GLOBAL']);
            fputcsv($out, ['Periode', $startDate.' s/d '.$endDate]);
            fputcsv($out, []);

            fputcsv($out, ['RINGKASAN']);
            foreach ($summary as $key => $val) {
                fputcsv($out, [strtoupper(str_replace('_', ' ', $key)), number_format($val, 2, ',', '.')]);
            }
            fputcsv($out, []);

            fputcsv($out, ['PEMASUKAN PER METODE']);
            foreach ($pemasukan['per_metode'] as $metode => $total) {
                fputcsv($out, [$metode, number_format($total, 2, ',', '.')]);
            }
            fputcsv($out, []);

            fputcsv($out, ['HPP PER KATEGORI']);
            foreach ($hpp['per_kategori'] as $kategori => $total) {
                fputcsv($out, [$kategori, number_format($total, 2, ',', '.')]);
            }
            fputcsv($out, []);

            fputcsv($out, ['PENGELUARAN OPERASIONAL PER KATEGORI']);
            foreach ($opex['per_kategori'] as $kategori => $total) {
                fputcsv($out, [$kategori, number_format($total, 2, ',', '.')]);
            }
            fputcsv($out, []);

            fputcsv($out, ['REKONSILIASI KAS & BANK']);
            fputcsv($out, ['Metode', 'Masuk', 'Keluar', 'Saldo']);
            foreach ($rekonsiliasi as $metode => $data) {
                fputcsv($out, [
                    strtoupper($metode),
                    number_format($data['masuk'], 2, ',', '.'),
                    number_format($data['keluar'], 2, ',', '.'),
                    number_format($data['saldo'], 2, ',', '.'),
                ]);
            }

            fclose($out);
        }, $filename, $headers);
    }

    /**
     * Resolve rentang tanggal dari filter, default bulan berjalan.
     */
    private function resolveRange(Request $request): array
    {
        $preset = $request->get('preset');

        $now = now();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($preset && ! $startDate && ! $endDate) {
            match ($preset) {
                'hari_ini' => [$startDate, $endDate] = [$now->format('Y-m-d'), $now->format('Y-m-d')],
                'bulan_ini' => [$startDate, $endDate] = [$now->startOfMonth()->format('Y-m-d'), $now->endOfMonth()->format('Y-m-d')],
                'tahun_ini' => [$startDate, $endDate] = [$now->startOfYear()->format('Y-m-d'), $now->endOfYear()->format('Y-m-d')],
                default => [$startDate, $endDate] = [$now->startOfMonth()->format('Y-m-d'), $now->endOfMonth()->format('Y-m-d')],
            };
        }

        $startDate = $startDate ?: $now->startOfMonth()->format('Y-m-d');
        $endDate = $endDate ?: $now->endOfMonth()->format('Y-m-d');

        return [$startDate, $endDate];
    }

    /**
     * Kategori produk (jenis cetakan) yang tersedia.
     */
    private function kategoriProduk(): array
    {
        return [
            'banner' => 'Digital Printing / Banner',
            'kertas' => 'Offset / Kertas',
            'stiker' => 'Stiker',
            'jasa' => 'Jasa Design',
            'lainnya' => 'Lainnya',
        ];
    }
}
