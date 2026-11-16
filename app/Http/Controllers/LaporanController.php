<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LaporanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_laporan_omzet', only: ['omzet']),
            new Middleware('permission:view_laporan_keuangan', only: ['keuangan']),
        ];
    }

    public function omzet(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $pesananLunas = Pesanan::with('pelanggan')
            ->where('status_pembayaran', 'lunas')
            ->whereBetween('tanggal_pesanan', [$startDate, $endDate])
            ->orderBy('tanggal_pesanan', 'desc')
            ->get();

        $totalOmzet = $pesananLunas->sum('total_harga');
        $totalPesanan = $pesananLunas->count();

        $omzetPerHari = Pesanan::select(
            DB::raw('DATE(tanggal_pesanan) as tanggal'),
            DB::raw('SUM(total_harga) as total')
        )
            ->where('status_pembayaran', 'lunas')
            ->whereBetween('tanggal_pesanan', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('laporan.omzet', compact(
            'pesananLunas',
            'totalOmzet',
            'totalPesanan',
            'omzetPerHari',
            'startDate',
            'endDate'
        ));
    }

    public function keuangan(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $pembayarans = Pembayaran::with(['pesanan.pelanggan', 'user'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalDP = $pembayarans->where('tipe', 'dp')->sum('jumlah');
        $totalPelunasan = $pembayarans->where('tipe', 'pelunasan')->sum('jumlah');
        $totalPembayaran = $pembayarans->sum('jumlah');

        $pembayaranPerMetode = Pembayaran::select(
            'metode_pembayaran',
            DB::raw('SUM(jumlah) as total')
        )
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('metode_pembayaran')
            ->get();

        $piutang = Pesanan::with('pelanggan')
            ->where('status_pembayaran', 'belum_lunas')
            ->orderBy('tanggal_pesanan', 'desc')
            ->get();

        $totalPiutang = $piutang->sum('sisa_bayar');

        return view('laporan.keuangan', compact(
            'pembayarans',
            'totalDP',
            'totalPelunasan',
            'totalPembayaran',
            'pembayaranPerMetode',
            'piutang',
            'totalPiutang',
            'startDate',
            'endDate'
        ));
    }

    public function pesanan(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status = $request->get('status', '');

        $query = Pesanan::with(['pelanggan', 'detailPesanans.bahan'])
            ->whereBetween('tanggal_pesanan', [$startDate, $endDate]);

        if ($status) {
            $query->where('status', $status);
        }

        $pesanans = $query->orderBy('tanggal_pesanan', 'desc')->paginate(20);

        $statusList = [
            'menunggu_desain' => 'Menunggu Desain',
            'siap_cetak' => 'Siap Cetak',
            'proses_cetak' => 'Proses Cetak',
            'selesai' => 'Selesai',
            'diambil' => 'Diambil',
        ];

        return view('laporan.pesanan', compact('pesanans', 'statusList', 'startDate', 'endDate', 'status'));
    }
}
