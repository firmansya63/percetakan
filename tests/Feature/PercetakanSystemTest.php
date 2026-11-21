<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\CashFlow;
use App\Models\DetailPesanan;
use App\Models\Finishing;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PercetakanSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_owner_can_access_all_modules(): void
    {
        $owner = User::where('email', 'owner@percetakan.com')->first();
        $this->assertNotNull($owner);

        $response = $this->actingAs($owner)->get('/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($owner)->get('/bahan');
        $response->assertStatus(200);

        $response = $this->actingAs($owner)->get('/finishing');
        $response->assertStatus(200);

        $response = $this->actingAs($owner)->get('/laporan/omzet');
        $response->assertStatus(200);

        $response = $this->actingAs($owner)->get('/laporan/keuangan');
        $response->assertStatus(200);
    }

    public function test_owner_can_create_bahan(): void
    {
        $owner = User::where('email', 'owner@percetakan.com')->first();

        $response = $this->actingAs($owner)->post('/bahan', [
            'nama_bahan' => 'Bahan Test',
            'satuan' => 'm2',
            'harga_dasar' => 10000,
            'harga_jual' => 15000,
            'stok' => 25,
            'jenis_cetakan' => 'banner',
            'keterangan' => 'Bahan untuk pengujian',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('bahan.index'));
        $this->assertDatabaseHas('bahans', [
            'nama_bahan' => 'Bahan Test',
            'harga_dasar' => 10000,
            'harga_jual' => 15000,
        ]);
    }

    public function test_owner_can_create_pelanggan(): void
    {
        $owner = User::where('email', 'owner@percetakan.com')->first();

        $response = $this->actingAs($owner)->post('/pelanggan', [
            'nama_pelanggan' => 'Pelanggan Test',
            'telepon' => '081234567890',
            'email' => 'pelanggan-test@example.com',
            'alamat' => 'Alamat Test',
            'catatan' => 'Catatan Test',
        ]);

        $response->assertRedirect(route('pelanggan.index'));
        $this->assertDatabaseHas('pelanggans', [
            'nama_pelanggan' => 'Pelanggan Test',
            'telepon' => '081234567890',
            'email' => 'pelanggan-test@example.com',
        ]);
    }

    public function test_uploading_all_designs_moves_the_order_to_the_production_queue(): void
    {
        Storage::fake('public');

        $owner = User::where('email', 'owner@percetakan.com')->first();
        $pesanan = Pesanan::create([
            'nomor_nota' => 'INV-TEST-ALL-DESIGNS',
            'user_id' => $owner->id,
            'tanggal_pesanan' => now()->toDateString(),
            'status' => 'menunggu_desain',
        ]);
        $bahan = Bahan::first();
        $firstItem = DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'bahan_id' => $bahan->id,
            'nama_item' => 'Item Pertama',
            'qty' => 1,
        ]);
        $secondItem = DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'bahan_id' => $bahan->id,
            'nama_item' => 'Item Kedua',
            'qty' => 1,
        ]);

        $firstResponse = $this->actingAs($owner)->post(route('detail-pesanan.upload', $firstItem), [
            'file_desain' => UploadedFile::fake()->image('item-pertama.jpg'),
        ]);

        $firstResponse->assertSessionHas('success', 'File desain berhasil diupload. Upload desain untuk item lainnya agar pesanan masuk antrean siap cetak.');
        $this->assertDatabaseHas('pesanans', ['id' => $pesanan->id, 'status' => 'menunggu_desain']);
        $firstItem->refresh();
        Storage::disk('public')->assertExists($firstItem->file_desain);

        $secondResponse = $this->actingAs($owner)->post(route('detail-pesanan.upload', $secondItem), [
            'file_desain' => UploadedFile::fake()->image('item-kedua.jpg'),
        ]);

        $secondResponse->assertSessionHas('success', 'File desain berhasil diupload. Pesanan masuk antrean siap cetak.');
        $this->assertDatabaseHas('pesanans', ['id' => $pesanan->id, 'status' => 'siap_cetak']);
        $secondItem->refresh();
        Storage::disk('public')->assertExists($secondItem->file_desain);
    }

    public function test_kasir_access_rights(): void
    {
        $kasir = User::where('email', 'kasir@percetakan.com')->first();
        $this->assertNotNull($kasir);

        // Can access dashboard & orders
        $response = $this->actingAs($kasir)->get('/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($kasir)->get('/pesanan');
        $response->assertStatus(200);

        $response = $this->actingAs($kasir)->get('/pesanan/create');
        $response->assertStatus(200);

        // CANNOT access master data (bahan/finishing) or omzet report
        $response = $this->actingAs($kasir)->get('/bahan');
        $response->assertStatus(403);

        $response = $this->actingAs($kasir)->get('/finishing');
        $response->assertStatus(403);

        $response = $this->actingAs($kasir)->get('/laporan/omzet');
        $response->assertStatus(403);
    }

    public function test_operator_access_rights(): void
    {
        $operator = User::where('email', 'operator@percetakan.com')->first();
        $this->assertNotNull($operator);

        // Can access production dashboard
        $response = $this->actingAs($operator)->get('/produksi');
        $response->assertStatus(200);

        // Accessing dashboard redirects operator to produksi
        $response = $this->actingAs($operator)->get('/dashboard');
        $response->assertRedirect('/produksi');

        // CANNOT access master prices or financial reports
        $response = $this->actingAs($operator)->get('/bahan');
        $response->assertStatus(403);

        $response = $this->actingAs($operator)->get('/laporan/omzet');
        $response->assertStatus(403);

        $response = $this->actingAs($operator)->get('/laporan/keuangan');
        $response->assertStatus(403);
    }

    public function test_completed_orders_are_recorded_on_the_production_queue(): void
    {
        $operator = User::where('email', 'operator@percetakan.com')->first();
        $bahan = Bahan::first();
        $pesanan = Pesanan::create([
            'nomor_nota' => 'INV-TEST-CETAK-SELESAI',
            'user_id' => $operator->id,
            'tanggal_pesanan' => now()->toDateString(),
            'status' => 'proses_cetak',
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'bahan_id' => $bahan->id,
            'nama_item' => 'Pesanan Sudah Dicetak',
            'qty' => 1,
        ]);

        $updateResponse = $this->actingAs($operator)->put(route('pesanan.update-status', $pesanan), [
            'status' => 'selesai',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('pesanans', [
            'id' => $pesanan->id,
            'status' => 'selesai',
        ]);

        $queueResponse = $this->actingAs($operator)->get(route('produksi.index'));

        $queueResponse->assertOk();
        $queueResponse->assertSeeText('Riwayat Sudah Dicetak');
        $queueResponse->assertSeeText('INV-TEST-CETAK-SELESAI');
        $queueResponse->assertSeeText('Pesanan Sudah Dicetak');
    }

    public function test_banner_and_kertas_order_calculation(): void
    {
        $kasir = User::where('email', 'kasir@percetakan.com')->first();
        $bahanBanner = Bahan::where('jenis_cetakan', 'banner')->first();
        $bahanKertas = Bahan::where('jenis_cetakan', 'kertas')->first();
        $finishing = Finishing::first();

        $postData = [
            'nama_pelanggan_baru' => 'PT Testing Mandiri',
            'telepon_pelanggan_baru' => '08123456789',
            'tanggal_pesanan' => now()->format('Y-m-d'),
            'items' => [
                [
                    'bahan_id' => $bahanBanner->id,
                    'nama_item' => 'Banner Outdoor 3x2',
                    'panjang' => 3,
                    'lebar' => 2,
                    'qty' => 2,
                    'finishings' => [$finishing->id],
                ],
                [
                    'bahan_id' => $bahanKertas->id,
                    'nama_item' => 'Brosur Promosi',
                    'qty' => 100,
                ],
            ],
            'dp' => 50000,
            'metode_pembayaran' => 'tunai',
        ];

        $bahanBannerStockBefore = $bahanBanner->stok;
        $bahanKertasStockBefore = $bahanKertas->stok;

        $response = $this->actingAs($kasir)->post('/pesanan', $postData);
        $response->assertRedirect();

        $pesanan = Pesanan::whereHas('pelanggan', function ($q) {
            $q->where('nama_pelanggan', 'PT Testing Mandiri');
        })->latest()->first();

        $this->assertNotNull($pesanan);

        // Expected banner: 3 * 2 * 2 * harga_jual + finishing->harga * 2
        $expectedBannerBahan = 3 * 2 * 2 * $bahanBanner->harga_jual;
        $expectedBannerFinishing = $finishing->harga * 2;
        $expectedBannerTotal = $expectedBannerBahan + $expectedBannerFinishing;

        // Expected kertas: 100 * harga_jual
        $expectedKertasTotal = 100 * $bahanKertas->harga_jual;

        $expectedGrandTotal = $expectedBannerTotal + $expectedKertasTotal;

        $this->assertEquals($expectedGrandTotal, $pesanan->total_harga);
        $this->assertEquals(50000, $pesanan->total_dp);
        $this->assertEquals($expectedGrandTotal - 50000, $pesanan->sisa_bayar);
        $this->assertEquals('belum_lunas', $pesanan->status_pembayaran);

        // Stok bahan berkurang sesuai konsumsi
        $this->assertEquals($bahanBannerStockBefore - 12, $bahanBanner->fresh()->stok);
        $this->assertEquals($bahanKertasStockBefore - 100, $bahanKertas->fresh()->stok);
    }

    public function test_pembayaran_lebih_dari_sisa_menyimpan_kembalian(): void
    {
        $kasir = User::where('email', 'kasir@percetakan.com')->first();
        $pesanan = Pesanan::create([
            'nomor_nota' => 'INV-TEST-KEMBALIAN',
            'user_id' => $kasir->id,
            'tanggal_pesanan' => now()->toDateString(),
            'total_harga' => 240000,
            'total_dp' => 0,
            'sisa_bayar' => 240000,
            'status_pembayaran' => 'belum_lunas',
        ]);

        $response = $this->actingAs($kasir)->post(route('pembayaran.store'), [
            'pesanan_id' => $pesanan->id,
            'jumlah' => 250000,
            'tipe' => 'pelunasan',
            'metode_pembayaran' => 'tunai',
        ]);

        $response->assertRedirect(route('pesanan.show', $pesanan));

        $this->assertDatabaseHas('pembayarans', [
            'pesanan_id' => $pesanan->id,
            'jumlah' => 250000,
            'kembalian' => 10000,
        ]);

        $pesanan->refresh();
        $this->assertEquals(0, $pesanan->sisa_bayar);
        $this->assertEquals('lunas', $pesanan->status_pembayaran);
    }

    public function test_pembayaran_tidak_lebih_dari_sisa_tidak_ada_kembalian(): void
    {
        $kasir = User::where('email', 'kasir@percetakan.com')->first();
        $pesanan = Pesanan::create([
            'nomor_nota' => 'INV-TEST-TANPA-KEMBALIAN',
            'user_id' => $kasir->id,
            'tanggal_pesanan' => now()->toDateString(),
            'total_harga' => 240000,
            'total_dp' => 0,
            'sisa_bayar' => 240000,
            'status_pembayaran' => 'belum_lunas',
        ]);

        $response = $this->actingAs($kasir)->post(route('pembayaran.store'), [
            'pesanan_id' => $pesanan->id,
            'jumlah' => 200000,
            'tipe' => 'pelunasan',
            'metode_pembayaran' => 'tunai',
        ]);

        $response->assertRedirect(route('pesanan.show', $pesanan));

        $this->assertDatabaseHas('pembayarans', [
            'pesanan_id' => $pesanan->id,
            'jumlah' => 200000,
            'kembalian' => 0,
        ]);

        $pesanan->refresh();
        $this->assertEquals(40000, $pesanan->sisa_bayar);
        $this->assertEquals('belum_lunas', $pesanan->status_pembayaran);
    }

    public function test_pesanan_dengan_jasa_design_harga_manual(): void
    {
        $kasir = User::where('email', 'kasir@percetakan.com')->first();
        $bahanKertas = Bahan::where('jenis_cetakan', 'kertas')->first();

        $response = $this->actingAs($kasir)->post('/pesanan', [
            'nama_pelanggan_baru' => 'PT Jasa Design',
            'telepon_pelanggan_baru' => '0812000000',
            'tanggal_pesanan' => now()->format('Y-m-d'),
            'items' => [
                [
                    'bahan_id' => $bahanKertas->id,
                    'nama_item' => 'Brosur',
                    'qty' => 100,
                ],
            ],
            'jasa_design' => 75000,
            'dp' => 0,
            'metode_pembayaran' => 'tunai',
        ]);

        $response->assertRedirect();

        $pesanan = Pesanan::whereHas('pelanggan', function ($q) {
            $q->where('nama_pelanggan', 'PT Jasa Design');
        })->latest()->first();

        $this->assertNotNull($pesanan);

        $jasaDesign = $pesanan->detailPesanans()->where('is_jasa_design', true)->first();
        $this->assertNotNull($jasaDesign);
        $this->assertNull($jasaDesign->bahan_id);
        $this->assertEquals('Jasa Design', $jasaDesign->nama_item);
        $this->assertEquals(75000, $jasaDesign->subtotal);

        $expectedBahan = 100 * $bahanKertas->harga_jual;
        $this->assertEquals($expectedBahan + 75000, $pesanan->total_harga);
    }

    public function test_pesanan_dengan_jasa_finishing_dan_potong(): void
    {
        $kasir = User::where('email', 'kasir@percetakan.com')->first();
        $bahanKertas = Bahan::where('jenis_cetakan', 'kertas')->first();

        $response = $this->actingAs($kasir)->post('/pesanan', [
            'nama_pelanggan_baru' => 'PT Jasa Potong',
            'telepon_pelanggan_baru' => '0812000001',
            'tanggal_pesanan' => now()->format('Y-m-d'),
            'items' => [
                [
                    'bahan_id' => $bahanKertas->id,
                    'nama_item' => 'Kartu Nama',
                    'qty' => 50,
                ],
            ],
            'jasa_potong' => 20000,
            'dp' => 0,
            'metode_pembayaran' => 'tunai',
        ]);

        $response->assertRedirect();

        $pesanan = Pesanan::whereHas('pelanggan', function ($q) {
            $q->where('nama_pelanggan', 'PT Jasa Potong');
        })->latest()->first();

        $this->assertNotNull($pesanan);

        $jasaPotong = $pesanan->detailPesanans()->where('is_jasa_design', true)->first();
        $this->assertNotNull($jasaPotong);
        $this->assertEquals('Jasa Potong', $jasaPotong->nama_item);
        $this->assertEquals(20000, $jasaPotong->subtotal);

        $expectedBahan = 50 * $bahanKertas->harga_jual;
        $this->assertEquals($expectedBahan + 20000, $pesanan->total_harga);
    }

    public function test_pesanan_dengan_beberapa_jasa_sekaligus(): void
    {
        $kasir = User::where('email', 'kasir@percetakan.com')->first();
        $bahanKertas = Bahan::where('jenis_cetakan', 'kertas')->first();

        $response = $this->actingAs($kasir)->post('/pesanan', [
            'nama_pelanggan_baru' => 'PT Multi Jasa',
            'telepon_pelanggan_baru' => '0812000002',
            'tanggal_pesanan' => now()->format('Y-m-d'),
            'items' => [
                [
                    'bahan_id' => $bahanKertas->id,
                    'nama_item' => 'Spanduk',
                    'qty' => 10,
                ],
            ],
            'jasa_design' => 50000,
            'jasa_finishing' => 30000,
            'jasa_potong' => 20000,
            'dp' => 0,
            'metode_pembayaran' => 'tunai',
        ]);

        $response->assertRedirect();

        $pesanan = Pesanan::whereHas('pelanggan', function ($q) {
            $q->where('nama_pelanggan', 'PT Multi Jasa');
        })->latest()->first();

        $this->assertNotNull($pesanan);

        $jasa = $pesanan->detailPesanans()->where('is_jasa_design', true)->get();
        $this->assertCount(3, $jasa);

        $expectedBahan = 10 * $bahanKertas->harga_jual;
        $this->assertEquals($expectedBahan + 100000, $pesanan->total_harga);
    }

    public function test_owner_can_access_laporan_shift(): void
    {
        $owner = User::where('email', 'owner@percetakan.com')->first();

        $response = $this->actingAs($owner)->get(route('laporan.shift'));

        $response->assertStatus(200);
        $response->assertSeeText('Laporan Keuangan per Shift');
    }

    public function test_kasir_cannot_access_laporan_shift(): void
    {
        $kasir = User::where('email', 'kasir@percetakan.com')->first();

        $response = $this->actingAs($kasir)->get(route('laporan.shift'));

        $response->assertStatus(403);
    }

    public function test_laporan_shift_menghitung_ringkasan_per_shift(): void
    {
        $owner = User::where('email', 'owner@percetakan.com')->first();

        // Transaksi shift 1 (08:00 - 16:00)
        CashFlow::create([
            'tanggal' => '2026-11-17',
            'tipe' => 'masuk',
            'kategori' => 'Pemasukan Lainnya',
            'jumlah' => 100000,
            'metode_pembayaran' => 'tunai',
            'user_id' => $owner->id,
        ]);
        CashFlow::create([
            'tanggal' => '2026-11-17',
            'tipe' => 'keluar',
            'kategori' => 'Operasional',
            'jumlah' => 30000,
            'user_id' => $owner->id,
        ]);

        // Set created_at agar masuk rentang shift 1
        CashFlow::query()->update(['created_at' => Carbon::parse('2026-11-17 10:00:00')]);

        $response = $this->actingAs($owner)->get(route('laporan.shift', [
            'tanggal' => '2026-11-17',
            'shift' => 1,
        ]));

        $response->assertOk();
        $response->assertSeeText('Rp 100.000');
        $response->assertSeeText('Rp 30.000');
        $response->assertSeeText('Rp 70.000'); // saldo
    }

    public function test_laporan_shift_memisahkan_dp_dan_pelunasan(): void
    {
        $owner = User::where('email', 'owner@percetakan.com')->first();

        $pesanan = Pesanan::create([
            'nomor_nota' => 'INV-SHIFT-DP',
            'user_id' => $owner->id,
            'tanggal_pesanan' => '2026-11-21',
            'total_harga' => 240000,
            'total_dp' => 0,
            'sisa_bayar' => 240000,
            'status_pembayaran' => 'belum_lunas',
        ]);

        // Pembayaran DP
        $dp = Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'user_id' => $owner->id,
            'jumlah' => 50000,
            'tipe' => 'dp',
            'metode_pembayaran' => 'tunai',
        ]);
        // Pembayaran Pelunasan
        $pelunasan = Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'user_id' => $owner->id,
            'jumlah' => 190000,
            'tipe' => 'pelunasan',
            'metode_pembayaran' => 'tunai',
        ]);

        // Cashflow sinkron untuk keduanya, dalam shift 1
        CashFlow::create([
            'tanggal' => '2026-11-21',
            'tipe' => 'masuk',
            'kategori' => 'Pembayaran DP',
            'jumlah' => 50000,
            'metode_pembayaran' => 'tunai',
            'pembayaran_id' => $dp->id,
            'user_id' => $owner->id,
        ]);
        CashFlow::create([
            'tanggal' => '2026-11-21',
            'tipe' => 'masuk',
            'kategori' => 'Pelunasan Pesanan',
            'jumlah' => 190000,
            'metode_pembayaran' => 'tunai',
            'pembayaran_id' => $pelunasan->id,
            'user_id' => $owner->id,
        ]);

        CashFlow::query()->update(['created_at' => Carbon::parse('2026-11-21 10:00:00')]);

        $response = $this->actingAs($owner)->get(route('laporan.shift', [
            'tanggal' => '2026-11-21',
            'shift' => 1,
        ]));

        $response->assertOk();
        $response->assertSeeText('Uang Muka (DP)');
        $response->assertSeeText('Pelunasan');
        $response->assertSeeText('Rp 50.000');  // DP
        $response->assertSeeText('Rp 190.000'); // Pelunasan
        $response->assertSeeText('Rp 240.000'); // total masuk
    }

    public function test_dp_saat_buat_pesanan_tersinkron_ke_cashflow(): void
    {
        $kasir = User::where('email', 'kasir@percetakan.com')->first();
        $bahanKertas = Bahan::where('jenis_cetakan', 'kertas')->first();

        $response = $this->actingAs($kasir)->post('/pesanan', [
            'nama_pelanggan_baru' => 'PT DP Sync',
            'telepon_pelanggan_baru' => '0812000099',
            'tanggal_pesanan' => now()->format('Y-m-d'),
            'items' => [
                [
                    'bahan_id' => $bahanKertas->id,
                    'nama_item' => 'Brosur',
                    'qty' => 100,
                ],
            ],
            'dp' => 50000,
            'metode_pembayaran' => 'tunai',
        ]);

        $response->assertRedirect();

        $pesanan = Pesanan::whereHas('pelanggan', function ($q) {
            $q->where('nama_pelanggan', 'PT DP Sync');
        })->latest()->first();

        $this->assertNotNull($pesanan);

        // DP harus tersinkron ke cashflow sebagai uang masuk.
        $this->assertDatabaseHas('cash_flows', [
            'pembayaran_id' => $pesanan->pembayarans()->where('tipe', 'dp')->first()->id,
            'tipe' => 'masuk',
            'kategori' => 'Pembayaran DP',
            'jumlah' => 50000,
        ]);
    }

    public function test_cashflow_filter_kategori_semua_menampilkan_dp_dan_pelunasan(): void
    {
        $owner = User::where('email', 'owner@percetakan.com')->first();

        CashFlow::create([
            'tanggal' => '2026-11-21',
            'tipe' => 'masuk',
            'kategori' => 'Pembayaran DP',
            'jumlah' => 50000,
            'metode_pembayaran' => 'tunai',
            'user_id' => $owner->id,
        ]);
        CashFlow::create([
            'tanggal' => '2026-11-21',
            'tipe' => 'masuk',
            'kategori' => 'Pelunasan Pesanan',
            'jumlah' => 190000,
            'metode_pembayaran' => 'tunai',
            'user_id' => $owner->id,
        ]);

        // Kategori = semua (kosong)
        $response = $this->actingAs($owner)->get(route('cashflow.index', [
            'start_date' => '2026-11-21',
            'end_date' => '2026-11-21',
        ]));

        $response->assertOk();
        $response->assertSeeText('Pembayaran DP');
        $response->assertSeeText('Pelunasan Pesanan');
    }
}
