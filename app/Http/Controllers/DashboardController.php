<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Bahan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if user is operator
        if ($user->hasRole('operator')) {
            return redirect()->route('produksi.index');
        }

        // Owner and Kasir see dashboard
        $totalPesanan = Pesanan::count();
        $pesananHariIni = Pesanan::whereDate('created_at', today())->count();
        $canViewOmzet = $user->can('view_laporan_omzet');
        $totalOmzet = $canViewOmzet ? Pesanan::where('status_pembayaran', 'lunas')->sum('total_harga') : 0;
        $pesananMenunggu = Pesanan::whereIn('status', ['menunggu_desain', 'siap_cetak'])->count();
        $pesananProses = Pesanan::where('status', 'proses_cetak')->count();

        $pesananTerbaru = Pesanan::with('pelanggan')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $pesananStatus = Pesanan::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();

        return view('dashboard', compact(
            'totalPesanan',
            'pesananHariIni',
            'canViewOmzet',
            'totalOmzet',
            'pesananMenunggu',
            'pesananProses',
            'pesananTerbaru',
            'pesananStatus'
        ));
    }
}
