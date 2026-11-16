<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_pelanggan',
        'telepon',
        'email',
        'alamat',
        'catatan',
    ];

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}
