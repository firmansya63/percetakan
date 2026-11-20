<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\DetailFinishing;
use App\Models\DetailPesanan;
use App\Models\Finishing;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PesananController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_pesanan', only: ['index', 'show']),
            new Middleware('permission:create_pesanan', only: ['create', 'store']),
            new Middleware('permission:edit_pesanan', only: ['edit', 'update']),
            new Middleware('permission:delete_pesanan', only: ['destroy']),
            new Middleware('permission:update_status_pesanan', only: ['updateStatus']),
        ];
    }

    public function index(Request $request)
    {
        $query = Pesanan::with(['pelanggan', 'detailPesanans.bahan']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nomor_nota', 'like', "%{$search}%")
                ->orWhereHas('pelanggan', function ($q) use ($search) {
                    $q->where('nama_pelanggan', 'like', "%{$search}%");
                });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $pesanans = $query->orderBy('created_at', 'desc')->paginate(10);

        $statusList = [
            'menunggu_desain' => 'Menunggu Desain',
            'siap_cetak' => 'Siap Cetak',
            'proses_cetak' => 'Proses Cetak',
            'selesai' => 'Selesai',
            'diambil' => 'Diambil',
        ];

        return view('pesanan.index', compact('pesanans', 'statusList'));
    }

    public function create()
    {
        $bahans = Bahan::where('is_active', true)->get();
        $finishings = Finishing::where('is_active', true)->get();
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();

        return view('pesanan.create', compact('bahans', 'finishings', 'pelanggans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggans,id',
            'nama_pelanggan_baru' => 'nullable|string|max:255',
            'telepon_pelanggan_baru' => 'nullable|string|max:20',
            'tanggal_pesanan' => 'required|date',
            'tanggal_ambil' => 'nullable|date',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.bahan_id' => 'required|exists:bahans,id',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.panjang' => 'nullable|numeric|min:0',
            'items.*.lebar' => 'nullable|numeric|min:0',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.catatan' => 'nullable|string',
            'items.*.finishings' => 'nullable|array',
            'jasa_design' => 'nullable|numeric|min:0',
            'dp' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'nullable|in:tunai,transfer,qris',
        ]);

        DB::beginTransaction();
        try {
            $pelangganId = null;
            if ($request->pelanggan_id) {
                $pelangganId = $request->pelanggan_id;
            } elseif ($request->nama_pelanggan_baru) {
                $pelanggan = Pelanggan::create([
                    'nama_pelanggan' => $request->nama_pelanggan_baru,
                    'telepon' => $request->telepon_pelanggan_baru,
                ]);
                $pelangganId = $pelanggan->id;
            }

            $pesanan = new Pesanan;
            $pesanan->nomor_nota = $pesanan->generateNomorNota();
            $pesanan->pelanggan_id = $pelangganId;
            $pesanan->user_id = Auth::id();
            $pesanan->tanggal_pesanan = $request->tanggal_pesanan;
            $pesanan->tanggal_ambil = $request->tanggal_ambil;
            $pesanan->catatan = $request->catatan;
            $pesanan->status = 'menunggu_desain';
            $pesanan->save();

            $totalSemuaItem = 0;

            foreach ($request->items as $itemData) {
                $bahan = Bahan::find($itemData['bahan_id']);

                // Hitung konsumsi stok bahan
                $stokDigunakan = $itemData['qty'];
                if ($bahan->jenis_cetakan === 'banner' && ! empty($itemData['panjang']) && ! empty($itemData['lebar'])) {
                    $stokDigunakan = $itemData['panjang'] * $itemData['lebar'] * $itemData['qty'];
                }

                // Validasi stok mencukupi
                if ($bahan->stok < $stokDigunakan) {
                    throw new \RuntimeException(
                        "Stok bahan \"{$bahan->nama_bahan}\" tidak mencukupi. Tersedia {$bahan->stok} {$bahan->satuan}, dibutuhkan {$stokDigunakan} {$bahan->satuan}."
                    );
                }

                $detailPesanan = new DetailPesanan;
                $detailPesanan->pesanan_id = $pesanan->id;
                $detailPesanan->bahan_id = $itemData['bahan_id'];
                $detailPesanan->nama_item = $itemData['nama_item'];
                $detailPesanan->qty = $itemData['qty'];
                $detailPesanan->harga_bahan = $bahan->harga_jual;
                $detailPesanan->catatan = $itemData['catatan'] ?? null;

                // Kalkulasi bahan
                if ($bahan->jenis_cetakan === 'banner' && ! empty($itemData['panjang']) && ! empty($itemData['lebar'])) {
                    $detailPesanan->panjang = $itemData['panjang'];
                    $detailPesanan->lebar = $itemData['lebar'];
                    $luas = $itemData['panjang'] * $itemData['lebar'];
                    $detailPesanan->subtotal_bahan = $luas * $itemData['qty'] * $bahan->harga_jual;
                } else {
                    $detailPesanan->subtotal_bahan = $itemData['qty'] * $bahan->harga_jual;
                }

                $detailPesanan->save();

                // Kalkulasi finishing
                $totalFinishing = 0;
                if (! empty($itemData['finishings'])) {
                    foreach ($itemData['finishings'] as $finishingId) {
                        $fin = Finishing::find($finishingId);
                        if ($fin) {
                            $finSubtotal = $fin->harga * $itemData['qty'];
                            DetailFinishing::create([
                                'detail_pesanan_id' => $detailPesanan->id,
                                'finishing_id' => $fin->id,
                                'harga' => $fin->harga,
                                'qty' => $itemData['qty'],
                                'subtotal' => $finSubtotal,
                            ]);
                            $totalFinishing += $finSubtotal;
                        }
                    }
                }

                $detailPesanan->total_finishing = $totalFinishing;
                $detailPesanan->subtotal = $detailPesanan->subtotal_bahan + $totalFinishing;
                $detailPesanan->save();

                // Kurangi stok bahan sesuai konsumsi
                $bahan->decrement('stok', $stokDigunakan);

                $totalSemuaItem += $detailPesanan->subtotal;
            }

            // Item Jasa Design (opsional, harga manual, tanpa bahan & stok)
            if ($request->filled('jasa_design') && $request->jasa_design > 0) {
                $jasaDesign = new DetailPesanan;
                $jasaDesign->pesanan_id = $pesanan->id;
                $jasaDesign->bahan_id = null;
                $jasaDesign->nama_item = 'Jasa Design';
                $jasaDesign->is_jasa_design = true;
                $jasaDesign->qty = 1;
                $jasaDesign->harga_bahan = $request->jasa_design;
                $jasaDesign->subtotal_bahan = $request->jasa_design;
                $jasaDesign->total_finishing = 0;
                $jasaDesign->subtotal = $request->jasa_design;
                $jasaDesign->catatan = 'Jasa desain (harga manual)';
                $jasaDesign->save();

                $totalSemuaItem += $jasaDesign->subtotal;
            }

            $pesanan->total_harga = $totalSemuaItem;
            $pesanan->sisa_bayar = $totalSemuaItem;
            $pesanan->status_pembayaran = 'belum_lunas';

            // Handle DP langsung saat buat pesanan
            if ($request->filled('dp') && $request->dp > 0) {
                $jumlahDp = min($request->dp, $pesanan->total_harga);
                Pembayaran::create([
                    'pesanan_id' => $pesanan->id,
                    'user_id' => Auth::id(),
                    'jumlah' => $jumlahDp,
                    'tipe' => ($jumlahDp >= $pesanan->total_harga) ? 'pelunasan' : 'dp',
                    'metode_pembayaran' => $request->metode_pembayaran ?? 'tunai',
                    'catatan' => 'Uang Muka saat pembuatan pesanan',
                ]);

                $pesanan->total_dp = $jumlahDp;
                $pesanan->sisa_bayar = max(0, $pesanan->total_harga - $jumlahDp);
                $pesanan->status_pembayaran = ($pesanan->sisa_bayar <= 0) ? 'lunas' : 'belum_lunas';
            }

            $pesanan->save();

            DB::commit();

            return redirect()->route('pesanan.show', $pesanan)
                ->with('success', 'Pesanan berhasil dibuat: '.$pesanan->nomor_nota);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['pelanggan', 'user', 'detailPesanans.bahan', 'detailPesanans.detailFinishings.finishing', 'pembayarans.user']);

        return view('pesanan.show', compact('pesanan'));
    }

    public function edit(Pesanan $pesanan)
    {
        $pesanan->load(['pelanggan', 'detailPesanans.bahan']);
        $bahans = Bahan::where('is_active', true)->get();
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();

        return view('pesanan.edit', compact('pesanan', 'bahans', 'pelanggans'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu_desain,siap_cetak,proses_cetak,selesai,diambil',
        ]);

        $pesanan->update($validated);

        return redirect()->route('pesanan.show', $pesanan)->with('success', 'Pesanan berhasil diperbarui!');
    }

    public function destroy(Pesanan $pesanan)
    {
        $pesanan->load('detailPesanans.bahan');

        DB::beginTransaction();
        try {
            foreach ($pesanan->detailPesanans as $detail) {
                if ($detail->bahan) {
                    $stokDigunakan = $detail->qty;
                    if ($detail->bahan->jenis_cetakan === 'banner' && $detail->panjang && $detail->lebar) {
                        $stokDigunakan = $detail->panjang * $detail->lebar * $detail->qty;
                    }
                    $detail->bahan->increment('stok', $stokDigunakan);
                }
            }

            $pesanan->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dihapus!');
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate(['status' => 'required|in:menunggu_desain,siap_cetak,proses_cetak,selesai,diambil']);
        $pesanan->status = $request->status;
        if ($request->status === 'diambil') {
            $pesanan->tanggal_ambil = now();
        }
        $pesanan->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui!');
    }

    public function invoice(Pesanan $pesanan)
    {
        $pesanan->load(['pelanggan', 'detailPesanans.bahan', 'detailPesanans.detailFinishings.finishing', 'pembayarans']);

        return view('pesanan.invoice', compact('pesanan'));
    }

    public function uploadDesain(Request $request, DetailPesanan $detailPesanan)
    {
        $request->validate([
            'file_desain' => 'required|file|max:512000', // up to 500MB without file type restriction to support all design formats (CDR, PSD, AI, PDF, etc.)
        ]);

        if ($request->hasFile('file_desain')) {
            // Delete old file if exists
            if ($detailPesanan->file_desain && Storage::disk('public')->exists($detailPesanan->file_desain)) {
                Storage::disk('public')->delete($detailPesanan->file_desain);
            }

            $file = $request->file('file_desain');
            $filename = time().'_'.preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('desain', $filename, 'public');

            $detailPesanan->file_desain = $path;
            $detailPesanan->save();

            $pesanan = $detailPesanan->pesanan()->with('detailPesanans')->first();
            $allItemsHaveDesign = $pesanan->detailPesanans->every(
                fn (DetailPesanan $detail) => filled($detail->file_desain),
            );

            if ($allItemsHaveDesign && $pesanan->status === 'menunggu_desain') {
                $pesanan->update(['status' => 'siap_cetak']);
            }

            return redirect()->back()->with('success', $allItemsHaveDesign
                ? 'File desain berhasil diupload. Pesanan masuk antrean siap cetak.'
                : 'File desain berhasil diupload. Upload desain untuk item lainnya agar pesanan masuk antrean siap cetak.');
        }

        return redirect()->back()->with('error', 'Gagal mengupload file desain.');
    }
}
