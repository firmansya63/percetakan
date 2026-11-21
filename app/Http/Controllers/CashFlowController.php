<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CashFlowController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_cashflow', only: ['index', 'show']),
            new Middleware('permission:create_cashflow', only: ['create', 'store']),
            new Middleware('permission:edit_cashflow', only: ['edit', 'update']),
            new Middleware('permission:delete_cashflow', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $tipe = $request->get('tipe', '');
        $kategori = $request->get('kategori', '');

        $query = CashFlow::with(['pembayaran.pesanan.pelanggan', 'user'])
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if (in_array($tipe, ['masuk', 'keluar'], true)) {
            $query->where('tipe', $tipe);
        }

        if (! empty($kategori)) {
            $query->where('kategori', $kategori);
        }

        $cashFlows = (clone $query)->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $totalMasuk = (clone $query)->where('tipe', 'masuk')->sum('jumlah');
        $totalKeluar = (clone $query)->where('tipe', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        // Ringkasan pemasukan per metode pembayaran (hanya uang masuk).
        $masukTransfer = (clone $query)->where('tipe', 'masuk')->where('metode_pembayaran', 'transfer')->sum('jumlah');
        $masukQris = (clone $query)->where('tipe', 'masuk')->where('metode_pembayaran', 'qris')->sum('jumlah');
        $masukTunai = (clone $query)->where('tipe', 'masuk')->where('metode_pembayaran', 'tunai')->sum('jumlah');

        $kategoriOptions = CashFlow::query()
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        // Daftar kategori lengkap untuk datalist form input (gabungan default + yang sudah ada di DB).
        $kategoriList = collect($this->defaultKategoriOptions())
            ->merge($kategoriOptions)
            ->unique()
            ->sort()
            ->values();

        return view('cashflow.index', compact(
            'cashFlows',
            'totalMasuk',
            'totalKeluar',
            'saldo',
            'masukTransfer',
            'masukQris',
            'masukTunai',
            'startDate',
            'endDate',
            'tipe',
            'kategori',
            'kategoriOptions',
            'kategoriList'
        ));
    }

    public function create(): View
    {
        return view('cashflow.create', [
            'kategoriOptions' => $this->defaultKategoriOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'tipe' => ['required', Rule::in(['masuk', 'keluar'])],
            'kategori' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:0.01',
            'metode_pembayaran' => ['nullable', Rule::in(['tunai', 'transfer', 'qris'])],
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();

        CashFlow::create($validated);

        return redirect()->route('cashflow.index')
            ->with('success', 'Transaksi cashflow berhasil ditambahkan.');
    }

    public function show(CashFlow $cashflow): RedirectResponse
    {
        return redirect()->route('cashflow.edit', $cashflow);
    }

    public function edit(CashFlow $cashflow): View|RedirectResponse
    {
        if ($cashflow->isSynced()) {
            return redirect()->route('cashflow.index')
                ->with('error', 'Transaksi yang disinkronkan dari pembayaran tidak dapat diubah. Ubah melalui data pembayaran terkait.');
        }

        return view('cashflow.edit', [
            'cashflow' => $cashflow,
            'kategoriOptions' => $this->defaultKategoriOptions(),
        ]);
    }

    public function update(Request $request, CashFlow $cashflow): RedirectResponse
    {
        if ($cashflow->isSynced()) {
            return redirect()->route('cashflow.index')
                ->with('error', 'Transaksi yang disinkronkan dari pembayaran tidak dapat diubah.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'tipe' => ['required', Rule::in(['masuk', 'keluar'])],
            'kategori' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:0.01',
            'metode_pembayaran' => ['nullable', Rule::in(['tunai', 'transfer', 'qris'])],
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $cashflow->update($validated);

        return redirect()->route('cashflow.index')
            ->with('success', 'Transaksi cashflow berhasil diperbarui.');
    }

    public function destroy(CashFlow $cashflow): RedirectResponse
    {
        if ($cashflow->isSynced()) {
            return redirect()->route('cashflow.index')
                ->with('error', 'Transaksi yang disinkronkan dari pembayaran tidak dapat dihapus.');
        }

        $cashflow->delete();

        return redirect()->route('cashflow.index')
            ->with('success', 'Transaksi cashflow berhasil dihapus.');
    }

    /**
     * @return array<int, string>
     */
    private function defaultKategoriOptions(): array
    {
        return [
            'Pembelian Bahan',
            'Operasional',
            'Gaji',
            'Listrik & Utilitas',
            'Sewa',
            'Perawatan & Peralatan',
            'Transportasi',
            'Pemasukan Lainnya',
            'Pengeluaran Lainnya',
        ];
    }
}
