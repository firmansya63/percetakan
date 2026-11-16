<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Finishing extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_finishing',
        'harga',
        'satuan_hitung',
        'keterangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function detailFinishings()
    {
        return $this->hasMany(DetailFinishing::class);
    }
}
