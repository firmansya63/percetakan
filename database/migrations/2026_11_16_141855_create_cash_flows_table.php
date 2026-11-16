<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->string('kategori');
            $table->decimal('jumlah', 15, 2);
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris'])->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('pembayaran_id')->nullable()->constrained('pembayarans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->timestamps();

            $table->index(['tanggal', 'tipe']);
            $table->unique('pembayaran_id');
        });

        $pembayarans = DB::table('pembayarans')->orderBy('id')->get();
        if ($pembayarans->isNotEmpty()) {
            $now = now();
            $rows = $pembayarans->map(function ($p) use ($now) {
                $kategori = $p->tipe === 'dp' ? 'Pembayaran DP' : 'Pelunasan Pesanan';
                $keterangan = trim('Sinkron dari pembayaran #' . $p->id . ($p->catatan ? ' - ' . $p->catatan : ''));

                return [
                    'tanggal' => substr((string) $p->created_at, 0, 10),
                    'tipe' => 'masuk',
                    'kategori' => $kategori,
                    'jumlah' => $p->jumlah,
                    'metode_pembayaran' => $p->metode_pembayaran,
                    'keterangan' => $keterangan,
                    'pembayaran_id' => $p->id,
                    'user_id' => $p->user_id,
                    'created_at' => $p->created_at ?? $now,
                    'updated_at' => $p->updated_at ?? $now,
                ];
            })->all();

            foreach (array_chunk($rows, 500) as $chunk) {
                DB::table('cash_flows')->insert($chunk);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};
