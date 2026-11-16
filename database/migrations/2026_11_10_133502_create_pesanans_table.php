<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_nota')->unique();
            $table->foreignId('pelanggan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->date('tanggal_pesanan');
            $table->date('tanggal_ambil')->nullable();
            $table->enum('status', [
                'menunggu_desain',
                'siap_cetak',
                'proses_cetak',
                'selesai',
                'diambil'
            ])->default('menunggu_desain');
            $table->text('catatan')->nullable();
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->decimal('total_dp', 15, 2)->default(0);
            $table->decimal('sisa_bayar', 15, 2)->default(0);
            $table->enum('status_pembayaran', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
