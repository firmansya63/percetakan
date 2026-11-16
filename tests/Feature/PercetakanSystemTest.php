<?php

namespace Tests\Feature;

use App\Models\Bahan;
use App\Models\DetailPesanan;
use App\Models\Finishing;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
    }
}
