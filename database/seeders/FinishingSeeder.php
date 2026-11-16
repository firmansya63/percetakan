<?php

namespace Database\Seeders;

use App\Models\Finishing;
use Illuminate\Database\Seeder;

class FinishingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $finishings = [
            [
                'nama_finishing' => 'Mata Ayam',
                'harga' => 5000,
                'satuan_hitung' => 'per_item',
                'keterangan' => 'Lubang untuk menggantung banner',
                'is_active' => true,
            ],
            [
                'nama_finishing' => 'Pocket/Kantong',
                'harga' => 10000,
                'satuan_hitung' => 'per_meter',
                'keterangan' => 'Kantong atas/bawah untuk masukkan kayu',
                'is_active' => true,
            ],
            [
                'nama_finishing' => 'Laminasi Glossy',
                'harga' => 5000,
                'satuan_hitung' => 'per_lembar',
                'keterangan' => 'Laminasi mengkilap',
                'is_active' => true,
            ],
            [
                'nama_finishing' => 'Laminasi Doff',
                'harga' => 5000,
                'satuan_hitung' => 'per_lembar',
                'keterangan' => 'Laminasi matte/tidak mengkilap',
                'is_active' => true,
            ],
            [
                'nama_finishing' => 'Lipat',
                'harga' => 2000,
                'satuan_hitung' => 'per_item',
                'keterangan' => 'Lipatan brosur',
                'is_active' => true,
            ],
            [
                'nama_finishing' => 'Cutting Sticker',
                'harga' => 15000,
                'satuan_hitung' => 'per_lembar',
                'keterangan' => 'Potong sticker sesuai desain',
                'is_active' => true,
            ],
            [
                'nama_finishing' => 'Jahit Sisir',
                'harga' => 8000,
                'satuan_hitung' => 'per_item',
                'keterangan' => 'Jahit pinggir dengan tali',
                'is_active' => true,
            ],
            [
                'nama_finishing' => 'Klem',
                'harga' => 5000,
                'satuan_hitung' => 'per_item',
                'keterangan' => 'Klem besi untuk banner',
                'is_active' => true,
            ],
            [
                'nama_finishing' => 'Hekter',
                'harga' => 1000,
                'satuan_hitung' => 'per_item',
                'keterangan' => 'Jepret brosur',
                'is_active' => true,
            ],
            [
                'nama_finishing' => 'Hard Cover',
                'harga' => 25000,
                'satuan_hitung' => 'per_item',
                'keterangan' => 'Sampul keras untuk buku/laporan',
                'is_active' => true,
            ],
        ];

        foreach ($finishings as $finishing) {
            Finishing::create($finishing);
        }

        $this->command->info('Finishing seeded successfully!');
    }
}
