<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ShiftReportController extends Controller implements HasMiddleware
{
    public const SHIFTS = [
        1 => ['label' => 'Shift 1 (07:00 - 15:30)', 'start' => '07:00:00', 'end' => '15:30:00'],
        2 => ['label' => 'Shift 2 (14:30 - 21:00)', 'start' => '14:30:00', 'end' => '21:00:00'],
    ];

    public static function middleware(): array
    {
        return [
            new Middleware('role:owner'),
        ];
    }

    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));
        $shift = (int) $request->get('shift', $this->currentShift());

        $shift = array_key_exists($shift, self::SHIFTS) ? $shift : 1;

        $start = Carbon::parse($tanggal . ' ' . self::SHIFTS[$shift]['start']);
        $end = Carbon::parse($tanggal . ' ' . self::SHIFTS[$shift]['end']);

        $query = CashFlow::with(['pembayaran.pesanan.pelanggan', 'user'])
            ->whereBetween('created_at', [$start, $end]);

        $cashFlows = (clone $query)->orderBy('created_at', 'asc')->get();

        $totalMasuk = (clone $query)->where('tipe', 'masuk')->sum('jumlah');
        $totalKeluar = (clone $query)->where('tipe', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        $perMetode = (clone $query)
            ->selectRaw('COALESCE(metode_pembayaran, \'lainnya\') as metode, SUM(jumlah) as total')
            ->where('tipe', 'masuk')
            ->groupBy('metode_pembayaran')
            ->orderByDesc('total')
            ->get();

        $perKategori = (clone $query)
            ->selectRaw('tipe, kategori, SUM(jumlah) as total, COUNT(*) as jumlah_transaksi')
            ->groupBy('tipe', 'kategori')
            ->orderBy('tipe')
            ->orderByDesc('total')
            ->get();

        $perKasir = (clone $query)
            ->selectRaw('user_id, SUM(jumlah) as total, COUNT(*) as jumlah_transaksi')
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->get();

        return view('laporan.shift', compact(
            'tanggal',
            'shift',
            'start',
            'end',
            'cashFlows',
            'totalMasuk',
            'totalKeluar',
            'saldo',
            'perMetode',
            'perKategori',
            'perKasir',
        ));
    }

    /**
     * Tentukan shift aktif berdasarkan jam sekarang.
     * Shift 1: 07:00 - 15:30, Shift 2: 14:30 - 21:00 (overlap 14:30 - 15:30).
     * Batas default: sebelum 15:00 dianggap Shift 1, setelahnya Shift 2.
     */
    private function currentShift(): int
    {
        $time = now()->format('H:i');

        if ($time >= '07:00' && $time < '15:00') {
            return 1;
        }

        if ($time >= '15:00' && $time < '21:00') {
            return 2;
        }

        // Di luar jam operasional: default ke shift 1.
        return 1;
    }
}
