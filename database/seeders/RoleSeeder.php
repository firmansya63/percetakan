<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Master Data
            'view_bahan',
            'create_bahan',
            'edit_bahan',
            'delete_bahan',
            'view_finishing',
            'create_finishing',
            'edit_finishing',
            'delete_finishing',

            // Transaksi
            'view_pesanan',
            'create_pesanan',
            'edit_pesanan',
            'delete_pesanan',
            'update_status_pesanan',

            // Pembayaran
            'view_pembayaran',
            'create_pembayaran',
            'view_harga', // Melihat data harga

            // Laporan
            'view_laporan_omzet',
            'view_laporan_keuangan',

            // Produksi
            'view_produksi',
            'download_desain',

            // Cashflow
            'view_cashflow',
            'create_cashflow',
            'edit_cashflow',
            'delete_cashflow',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // 1. Owner / Admin - Full Access
        $ownerRole = Role::findOrCreate('owner');
        $ownerRole->givePermissionTo(Permission::all());

        // 2. Kasir / Frontdesk
        $kasirRole = Role::findOrCreate('kasir');
        $kasirRole->givePermissionTo([
            'view_pesanan',
            'create_pesanan',
            'edit_pesanan',
            'delete_pesanan',
            'view_pembayaran',
            'create_pembayaran',
            'view_harga',
            'view_produksi',
        ]);
        // CANNOT: view_bahan (master), view_laporan_omzet, view_laporan_keuangan

        // 3. Operator Produksi
        $operatorRole = Role::findOrCreate('operator');
        $operatorRole->givePermissionTo([
            'view_produksi',
            'update_status_pesanan',
            'download_desain',
        ]);
        // CANNOT: view_harga, view_pembayaran, view_laporan_*, etc.

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        if (isset($this->command)) {
            $this->command->info('Roles and Permissions seeded successfully!');
        }
    }
}
