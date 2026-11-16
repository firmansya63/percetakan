<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PembayaranController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_pembayaran', only: ['index', 'show']),
            new Middleware('permission:create_pembayaran', only: ['create', 'store']),
        ];
    }

    public function index(Request $request)
    {
        $query = Pembayaran::with(['pesanan.pelanggan', 'user']);

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->has('tipe') && $request->tipe !== '') {
            $query->where('tipe', $request->tipe);
        }

        $pembayarans = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('pembayaran.index', compact('pembayarans'));
    }

    public function create(Pesanan $pesanan)
    {
        $pesanan->load(['pelanggan', 'detailPesanans.bahan']);
        
        return view('pembayaran.create', compact('pesanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'jumlah' => 'required|numeric|min:1',
            'tipe' => 'required|in:dp,pelunasan',
            'metode_pembayaran' => 'required|in:tunai,transfer,qris',
            'catatan' => 'nullable|string',
        ]);

        $pesanan = Pesanan::findOrFail($request->pesanan_id);

        // Create pembayaran
        $pembayaran = new Pembayaran();
        $pembayaran->pesanan_id = $pesanan->id;
        $pembayaran->user_id = Auth::id();
        $pembayaran->jumlah = $request->jumlah;
        $pembayaran->tipe = $request->tipe;
        $pembayaran->metode_pembayaran = $request->metode_pembayaran;
        $pembayaran->catatan = $request->catatan;
        $pembayaran->save();

        // Update pesanan payment info
        $pesanan->total_dp += $request->jumlah;
        $pesanan->sisa_bayar = $pesanan->total_harga - $pesanan->total_dp;
        
        if ($pesanan->sisa_bayar <= 0) {
            $pesanan->status_pembayaran = 'lunas';
            $pesanan->sisa_bayar = 0;
        }
        
        $pesanan->save();

        return redirect()->route('pesanan.show', $pesanan)
            ->with('success', 'Pembayaran berhasil dicatat!');
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['pesanan.pelanggan', 'user']);
        
        return view('pembayaran.show', compact('pembayaran'));
    }

    public function receipt(Pembayaran $pembayaran)
    {
        $pembayaran->load(['pesanan.pelanggan', 'pesanan.detailPesanans.bahan', 'user']);
        
        return view('pembayaran.receipt', compact('pembayaran'));
    }
}
