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
        Schema::create('detail_pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bahan_id')->constrained()->restrictOnDelete();
            $table->string('nama_item');
            $table->text('deskripsi')->nullable();
            $table->decimal('panjang', 8, 2)->nullable()->comment('dalam meter, untuk banner');
            $table->decimal('lebar', 8, 2)->nullable()->comment('dalam meter, untuk banner');
            $table->integer('qty')->default(1);
            $table->decimal('harga_bahan', 12, 2)->default(0);
            $table->decimal('subtotal_bahan', 15, 2)->default(0);
            $table->decimal('total_finishing', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->string('file_desain')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
    }
};
