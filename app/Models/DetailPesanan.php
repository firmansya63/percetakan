<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $fillable = [
        'pesanan_id',
        'bahan_id',
        'nama_item',
        'deskripsi',
        'panjang',
        'lebar',
        'qty',
        'harga_bahan',
        'subtotal_bahan',
        'total_finishing',
        'subtotal',
        'file_desain',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'panjang' => 'decimal:2',
            'lebar' => 'decimal:2',
            'harga_bahan' => 'decimal:2',
            'subtotal_bahan' => 'decimal:2',
            'total_finishing' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }

    public function detailFinishings()
    {
        return $this->hasMany(DetailFinishing::class);
    }

    public function hitungSubtotal()
    {
        $bahan = $this->bahan;

        if ($bahan->jenis_cetakan === 'banner' && $this->panjang && $this->lebar) {
            // Kalkulasi untuk banner: (Panjang x Lebar) x Qty x Harga
            $luas = $this->panjang * $this->lebar;
            $this->subtotal_bahan = $luas * $this->qty * $this->harga_bahan;
        } else {
            // Kalkulasi untuk kertas: Qty x Harga
            $this->subtotal_bahan = $this->qty * $this->harga_bahan;
        }

        $this->total_finishing = $this->detailFinishings()->sum('subtotal');
        $this->subtotal = $this->subtotal_bahan + $this->total_finishing;
        $this->save();

        return $this->subtotal;
    }
}
