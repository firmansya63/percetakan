<?php

namespace App\Console\Commands;

use App\Models\CashFlow;
use App\Models\Pembayaran;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncPaymentsToCashflow extends Command
{
    protected $signature = 'cashflow:sync-payments';

    protected $description = 'Sinkronkan semua pembayaran yang belum memiliki cashflow ke tabel cash_flows';

    public function handle(): int
    {
        $pembayarans = Pembayaran::with('pesanan')
            ->whereDoesntHave('cashFlow')
            ->orderBy('id')
            ->get();

        if ($pembayarans->isEmpty()) {
            $this->info('Tidak ada pembayaran yang perlu disinkronkan.');

            return self::SUCCESS;
        }

        $this->info("Ditemukan {$pembayarans->count()} pembayaran yang belum disinkronkan.");

        DB::transaction(function () use ($pembayarans) {
            foreach ($pembayarans as $pembayaran) {
                $kategori = $pembayaran->tipe === 'dp' ? 'Pembayaran DP' : 'Pelunasan Pesanan';
                $keterangan = trim('Sinkron dari pembayaran #' . $pembayaran->id . ' (' . ($pembayaran->pesanan->nomor_nota ?? '-') . ')' . ($pembayaran->catatan ? ' - ' . $pembayaran->catatan : ''));

                // Gunakan forceFill agar created_at/updated_at mengikuti waktu transaksi
                // (bukan now()), sebab kolom tersebut tidak ada di $fillable.
                CashFlow::forceCreate([
                    'tanggal' => $pembayaran->created_at->toDateString(),
                    'tipe' => 'masuk',
                    'kategori' => $kategori,
                    'jumlah' => $pembayaran->jumlah,
                    'metode_pembayaran' => $pembayaran->metode_pembayaran,
                    'keterangan' => $keterangan,
                    'pembayaran_id' => $pembayaran->id,
                    'user_id' => $pembayaran->user_id,
                    'created_at' => $pembayaran->created_at,
                    'updated_at' => $pembayaran->updated_at,
                ]);
            }
        });

        $this->info("Berhasil menyinkronkan {$pembayarans->count()} pembayaran ke cashflow.");

        return self::SUCCESS;
    }
}
