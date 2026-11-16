<?php

use App\Http\Controllers\BahanController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinishingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data (Admin Only)
    Route::resource('bahan', BahanController::class);
    Route::resource('finishing', FinishingController::class);
    Route::get('bahan/{bahan}/harga', [BahanController::class, 'getHarga'])->name('bahan.harga');

    // Pelanggan
    Route::resource('pelanggan', PelangganController::class);
    Route::get('pelanggan-search', [PelangganController::class, 'search'])->name('pelanggan.search');

    // Pesanan
    Route::resource('pesanan', PesananController::class);
    Route::put('pesanan/{pesanan}/status', [PesananController::class, 'updateStatus'])->name('pesanan.update-status');
    Route::post('detail-pesanan/{detailPesanan}/upload', [PesananController::class, 'uploadDesain'])->name('detail-pesanan.upload');
    Route::get('pesanan/{pesanan}/invoice', [PesananController::class, 'invoice'])->name('pesanan.invoice');
    Route::post('calculate-price', [PesananController::class, 'calculatePrice'])->name('pesanan.calculate-price');

    // Pembayaran
    Route::resource('pembayaran', PembayaranController::class)->except(['create']);
    Route::get('pembayaran/create/{pesanan}', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::get('pembayaran/{pembayaran}/receipt', [PembayaranController::class, 'receipt'])->name('pembayaran.receipt');

    // Produksi Dashboard
    Route::get('produksi', [ProduksiController::class, 'index'])->name('produksi.index');
    Route::get('produksi/selesai', [ProduksiController::class, 'selesai'])->name('produksi.selesai');

    Route::get('produksi/{pesanan}', [ProduksiController::class, 'show'])->name('produksi.show');
    Route::get('produksi/download/{detailPesanan}', [ProduksiController::class, 'downloadDesain'])->name('produksi.download');

    // Laporan
    Route::get('laporan/omzet', [LaporanController::class, 'omzet'])->name('laporan.omzet');
    Route::get('laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
    Route::get('laporan/pesanan', [LaporanController::class, 'pesanan'])->name('laporan.pesanan');

    // Cashflow / Keuangan
    Route::resource('cashflow', CashFlowController::class);

    // User Management
    Route::resource('user', UserController::class)->except(['show']);

});

require __DIR__.'/auth.php';
