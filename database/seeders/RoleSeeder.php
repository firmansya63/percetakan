<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        // Master Data Permissions
        Permission::create(['name' => 'view_bahan']);
        Permission::create(['name' => 'create_bahan']);
        Permission::create(['name' => 'edit_bahan']);
        Permission::create(['name' => 'delete_bahan']);
        
        Permission::create(['name' => 'view_finishing']);
        Permission::create(['name' => 'create_finishing']);
        Permission::create(['name' => 'edit_finishing']);
        Permission::create(['name' => 'delete_finishing']);

        // Transaksi Permissions
        Permission::create(['name' => 'view_pesanan']);
        Permission::create(['name' => 'create_pesanan']);
        Permission::create(['name' => 'edit_pesanan']);
        Permission::create(['name' => 'delete_pesanan']);
        Permission::create(['name' => 'update_status_pesanan']);

        // Pembayaran Permissions
        Permission::create(['name' => 'view_pembayaran']);
        Permission::create(['name' => 'create_pembayaran']);
        Permission::create(['name' => 'view_harga']); // Melihat data harga

        // Laporan Permissions
        Permission::create(['name' => 'view_laporan_omzet']);
        Permission::create(['name' => 'view_laporan_keuangan']);

        // Produksi Permissions
        Permission::create(['name' => 'view_produksi']);
        Permission::create(['name' => 'download_desain']);

        // Cashflow Permissions
        Permission::create(['name' => 'view_cashflow']);
        Permission::create(['name' => 'create_cashflow']);
        Permission::create(['name' => 'edit_cashflow']);
        Permission::create(['name' => 'delete_cashflow']);

        // Create Roles and Assign Permissions

        // 1. Owner / Admin - Full Access
        $ownerRole = Role::create(['name' => 'owner']);
        $ownerRole->givePermissionTo(Permission::all());

        // 2. Kasir / Frontdesk
        $kasirRole = Role::create(['name' => 'kasir']);
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
        $operatorRole = Role::create(['name' => 'operator']);
        $operatorRole->givePermissionTo([
            'view_produksi',
            'update_status_pesanan',
            'download_desain',
        ]);
        // CANNOT: view_harga, view_pembayaran, view_laporan_*, etc.

        $this->command->info('Roles and Permissions seeded successfully!');
    }
}
