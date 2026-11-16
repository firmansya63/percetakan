<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailFinishing extends Model
{
    protected $fillable = [
        'detail_pesanan_id',
        'finishing_id',
        'harga',
        'qty',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function detailPesanan()
    {
        return $this->belongsTo(DetailPesanan::class);
    }

    public function finishing()
    {
        return $this->belongsTo(Finishing::class);
    }
}
