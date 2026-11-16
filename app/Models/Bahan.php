<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bahan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_bahan',
        'satuan',
        'harga_dasar',
        'harga_jual',
        'stok',
        'jenis_cetakan',
        'keterangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga_dasar' => 'decimal:2',
            'harga_jual' => 'decimal:2',
            'stok' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
