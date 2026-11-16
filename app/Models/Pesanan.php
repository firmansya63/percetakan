<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nomor_nota',
        'pelanggan_id',
        'user_id',
        'tanggal_pesanan',
        'tanggal_ambil',
        'status',
        'catatan',
        'total_harga',
        'total_dp',
        'sisa_bayar',
        'status_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pesanan' => 'date',
            'tanggal_ambil' => 'date',
            'total_harga' => 'decimal:2',
            'total_dp' => 'decimal:2',
            'sisa_bayar' => 'decimal:2',
        ];
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function generateNomorNota()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $lastPesanan = self::withTrashed()
            ->whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastPesanan ? (intval(substr($lastPesanan->nomor_nota, -4)) + 1) : 1;

        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function updateTotalHarga()
    {
        $this->total_harga = $this->detailPesanans()->sum('subtotal');
        $this->sisa_bayar = $this->total_harga - $this->total_dp;
        $this->status_pembayaran = $this->sisa_bayar <= 0 ? 'lunas' : 'belum_lunas';
        $this->save();
    }
}
