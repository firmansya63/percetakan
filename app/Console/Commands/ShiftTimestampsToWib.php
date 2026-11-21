<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ShiftTimestampsToWib extends Command
{
    protected $signature = 'timezone:shift-to-wib
                            {hours=7 : Jumlah jam yang ditambahkan (default 7)}
                            {--force : Lewati konfirmasi}';

    protected $description = 'Geser timestamp historis (+7 jam) agar konsisten dengan timezone Asia/Jakarta (WIB). Hanya jalankan SEKALI.';

    /**
     * Tabel dan kolom datetime yang perlu digeser.
     */
    private const TABLES = [
        'pesanans' => ['created_at', 'updated_at'],
        'pembayarans' => ['created_at', 'updated_at'],
        'cash_flows' => ['created_at', 'updated_at'],
        'detail_pesanans' => ['created_at', 'updated_at'],
        'detail_finishings' => ['created_at', 'updated_at'],
        'pelanggans' => ['created_at', 'updated_at'],
    ];

    public function handle(): int
    {
        $hours = (int) $this->argument('hours');

        if ($hours <= 0) {
            $this->error('Jumlah jam harus lebih dari 0.');

            return self::FAILURE;
        }

        $this->warn("Operasi ini akan menggeser timestamp +{$hours} jam pada tabel-tabel transaksi.");
        $this->warn('PERINGATAN: Jalankan hanya SEKALI. Menjalankan ulang akan menggeser data dua kali.');

        if (! $this->option('force') && ! $this->confirm('Lanjutkan?', false)) {
            $this->info('Dibatalkan.');

            return self::SUCCESS;
        }

        $this->warn("Menggeser timestamp +{$hours} jam pada tabel-tabel transaksi.");

        foreach (self::TABLES as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $sets = collect($columns)
                ->map(fn ($col) => "`{$col}` = DATE_ADD(`{$col}`, INTERVAL {$hours} HOUR)")
                ->implode(', ');

            DB::statement("UPDATE `{$table}` SET {$sets}");

            $this->line("  - {$table}: " . implode(', ', $columns));
        }

        // Kolom tanggal (date) dihitung ulang dari created_at yang sudah digeser.
        if (Schema::hasTable('cash_flows') && Schema::hasColumn('cash_flows', 'tanggal')) {
            DB::statement('UPDATE `cash_flows` SET `tanggal` = DATE(`created_at`)');
            $this->line('  - cash_flows.tanggal dihitung ulang dari created_at.');
        }

        $this->info('Selesai. Timestamp telah digeser ke WIB.');

        return self::SUCCESS;
    }
}
