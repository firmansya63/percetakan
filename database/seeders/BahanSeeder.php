<?php

namespace Database\Seeders;

use App\Models\Bahan;
use Illuminate\Database\Seeder;

class BahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bahans = [
            // Banner Materials
            [
                'nama_bahan' => 'Flexi 280g',
                'satuan' => 'm2',
                'harga_dasar' => 15000,
                'harga_jual' => 25000,
                'stok' => 100,
                'jenis_cetakan' => 'banner',
                'keterangan' => 'Bahan flexi standar untuk banner outdoor',
                'is_active' => true,
            ],
            [
                'nama_bahan' => 'Flexi 440g',
                'satuan' => 'm2',
                'harga_dasar' => 20000,
                'harga_jual' => 35000,
                'stok' => 80,
                'jenis_cetakan' => 'banner',
                'keterangan' => 'Bahan flexi tebal untuk billboard',
                'is_active' => true,
            ],
            [
                'nama_bahan' => 'Albatross',
                'satuan' => 'm2',
                'harga_dasar' => 25000,
                'harga_jual' => 45000,
                'stok' => 50,
                'jenis_cetakan' => 'banner',
                'keterangan' => 'Bahan premium untuk indoor signage',
                'is_active' => true,
            ],
            [
                'nama_bahan' => 'China Flex',
                'satuan' => 'm2',
                'harga_dasar' => 12000,
                'harga_jual' => 20000,
                'stok' => 150,
                'jenis_cetakan' => 'banner',
                'keterangan' => 'Bahan flexi ekonomis',
                'is_active' => true,
            ],
            // Kertas Materials
            [
                'nama_bahan' => 'Art Carton 260g',
                'satuan' => 'lembar',
                'harga_dasar' => 2500,
                'harga_jual' => 5000,
                'stok' => 500,
                'jenis_cetakan' => 'kertas',
                'keterangan' => 'Kertas tebal untuk brosur, kartu nama',
                'is_active' => true,
            ],
            [
                'nama_bahan' => 'Art Carton 310g',
                'satuan' => 'lembar',
                'harga_dasar' => 3500,
                'harga_jual' => 7000,
                'stok' => 300,
                'jenis_cetakan' => 'kertas',
                'keterangan' => 'Kertas sangat tebal untuk undangan',
                'is_active' => true,
            ],
            [
                'nama_bahan' => 'HVS 80g',
                'satuan' => 'rim',
                'harga_dasar' => 30000,
                'harga_jual' => 50000,
                'stok' => 100,
                'jenis_cetakan' => 'kertas',
                'keterangan' => 'Kertas standar untuk dokumen',
                'is_active' => true,
            ],
            [
                'nama_bahan' => 'Photo Paper Glossy',
                'satuan' => 'lembar',
                'harga_dasar' => 5000,
                'harga_jual' => 10000,
                'stok' => 200,
                'jenis_cetakan' => 'kertas',
                'keterangan' => 'Kertas foto glossy',
                'is_active' => true,
            ],
            [
                'nama_bahan' => 'Sticker Vinyl',
                'satuan' => 'm2',
                'harga_dasar' => 20000,
                'harga_jual' => 40000,
                'stok' => 60,
                'jenis_cetakan' => 'banner',
                'keterangan' => 'Sticker vinyl untuk cutting',
                'is_active' => true,
            ],
            [
                'nama_bahan' => 'Neon Box',
                'satuan' => 'm2',
                'harga_dasar' => 50000,
                'harga_jual' => 100000,
                'stok' => 30,
                'jenis_cetakan' => 'banner',
                'keterangan' => 'Bahan untuk neon box',
                'is_active' => true,
            ],
        ];

        foreach ($bahans as $bahan) {
            Bahan::create($bahan);
        }

        $this->command->info('Bahan seeded successfully!');
    }
}
