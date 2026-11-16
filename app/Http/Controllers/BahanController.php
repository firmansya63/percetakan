<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BahanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_bahan', only: ['index', 'show']),
            new Middleware('permission:create_bahan', only: ['create', 'store']),
            new Middleware('permission:edit_bahan', only: ['edit', 'update']),
            new Middleware('permission:delete_bahan', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Bahan::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_bahan', 'like', "%{$search}%");
        }

        if ($request->has('jenis_cetakan') && $request->jenis_cetakan !== '') {
            $query->where('jenis_cetakan', $request->jenis_cetakan);
        }

        $bahans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('bahan.index', compact('bahans'));
    }

    public function create()
    {
        return view('bahan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan' => 'required|in:m2,rim,lembar,meter',
            'harga_dasar' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'jenis_cetakan' => 'required|in:banner,kertas,lainnya',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Bahan::create($validated);

        return redirect()->route('bahan.index')
            ->with('success', 'Bahan berhasil ditambahkan!');
    }

    public function show(Bahan $bahan)
    {
        return view('bahan.show', compact('bahan'));
    }

    public function edit(Bahan $bahan)
    {
        return view('bahan.edit', compact('bahan'));
    }

    public function update(Request $request, Bahan $bahan)
    {
        $validated = $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan' => 'required|in:m2,rim,lembar,meter',
            'harga_dasar' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'jenis_cetakan' => 'required|in:banner,kertas,lainnya',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $bahan->update($validated);

        return redirect()->route('bahan.index')
            ->with('success', 'Bahan berhasil diperbarui!');
    }

    public function destroy(Bahan $bahan)
    {
        $bahan->delete();

        return redirect()->route('bahan.index')
            ->with('success', 'Bahan berhasil dihapus!');
    }

    public function getHarga(Bahan $bahan)
    {
        return response()->json([
            'harga_jual' => $bahan->harga_jual,
            'jenis_cetakan' => $bahan->jenis_cetakan,
            'satuan' => $bahan->satuan,
        ]);
    }
}
