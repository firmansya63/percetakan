<?php

namespace App\Http\Controllers;

use App\Models\Finishing;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FinishingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_finishing', only: ['index', 'show']),
            new Middleware('permission:create_finishing', only: ['create', 'store']),
            new Middleware('permission:edit_finishing', only: ['edit', 'update']),
            new Middleware('permission:delete_finishing', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Finishing::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_finishing', 'like', "%{$search}%");
        }

        $finishings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('finishing.index', compact('finishings'));
    }

    public function create()
    {
        return view('finishing.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_finishing' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'satuan_hitung' => 'required|in:per_item,per_meter,per_lembar',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Finishing::create($validated);

        return redirect()->route('finishing.index')
            ->with('success', 'Finishing berhasil ditambahkan!');
    }

    public function show(Finishing $finishing)
    {
        return view('finishing.show', compact('finishing'));
    }

    public function edit(Finishing $finishing)
    {
        return view('finishing.edit', compact('finishing'));
    }

    public function update(Request $request, Finishing $finishing)
    {
        $validated = $request->validate([
            'nama_finishing' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'satuan_hitung' => 'required|in:per_item,per_meter,per_lembar',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $finishing->update($validated);

        return redirect()->route('finishing.index')
            ->with('success', 'Finishing berhasil diperbarui!');
    }

    public function destroy(Finishing $finishing)
    {
        $finishing->delete();

        return redirect()->route('finishing.index')
            ->with('success', 'Finishing berhasil dihapus!');
    }

    public function getHarga(Finishing $finishing)
    {
        return response()->json([
            'harga' => $finishing->harga,
            'satuan_hitung' => $finishing->satuan_hitung,
        ]);
    }
}
