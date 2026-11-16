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
        Schema::create('detail_finishings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_pesanan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('finishing_id')->constrained()->restrictOnDelete();
            $table->decimal('harga', 12, 2)->default(0);
            $table->integer('qty')->default(1);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_finishings');
    }
};
