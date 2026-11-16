<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProduksiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_produksi', only: ['index', 'show', 'selesai']),
            new Middleware('permission:update_status_pesanan', only: ['updateStatus']),
            new Middleware('permission:download_desain', only: ['downloadDesain']),
        ];
    }

    public function index()
    {
        $pesananSiapCetak = Pesanan::with(['detailPesanans.bahan', 'pelanggan'])
            ->where('status', 'siap_cetak')
            ->orderBy('tanggal_pesanan', 'asc')
            ->get();

        $pesananProsesCetak = Pesanan::with(['detailPesanans.bahan', 'pelanggan'])
            ->where('status', 'proses_cetak')
            ->orderBy('tanggal_pesanan', 'asc')
            ->get();

        $pesananSelesai = Pesanan::with(['detailPesanans.bahan', 'pelanggan'])
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('produksi.index', compact('pesananSiapCetak', 'pesananProsesCetak', 'pesananSelesai'));
    }

    public function selesai()
    {
        $pesananSelesai = Pesanan::with(['detailPesanans.bahan', 'pelanggan'])
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('produksi.selesai', compact('pesananSelesai'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['detailPesanans.bahan', 'detailPesanans.detailFinishings.finishing']);

        return view('produksi.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'status' => 'required|in:siap_cetak,proses_cetak,selesai',
        ]);

        $pesanan->status = $validated['status'];
        
        if ($validated['status'] === 'selesai') {
            $pesanan->tanggal_ambil = now();
        }
        
        $pesanan->save();

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function downloadDesain(DetailPesanan $detailPesanan)
    {
        if (!$detailPesanan->file_desain) {
            return redirect()->back()
                ->with('error', 'File desain tidak tersedia!');
        }

        $filePath = storage_path('app/public/' . $detailPesanan->file_desain);

        if (!file_exists($filePath)) {
            return redirect()->back()
                ->with('error', 'File desain tidak ditemukan!');
        }

        return response()->download($filePath);
    }
}
