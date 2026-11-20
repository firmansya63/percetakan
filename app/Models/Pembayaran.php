<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'pesanan_id',
        'user_id',
        'jumlah',
        'kembalian',
        'tipe',
        'metode_pembayaran',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'kembalian' => 'decimal:2',
        ];
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
