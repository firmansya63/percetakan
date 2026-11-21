<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_laporan_global',
            'view_setoran_harian',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $owner = Role::where('name', 'owner')->where('guard_name', 'web')->first();
        if ($owner) {
            $owner->givePermissionTo($permissions);
        }

        $kasir = Role::where('name', 'kasir')->where('guard_name', 'web')->first();
        if ($kasir) {
            $kasir->givePermissionTo('view_setoran_harian');
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_laporan_global',
            'view_setoran_harian',
        ];

        $owner = Role::where('name', 'owner')->where('guard_name', 'web')->first();
        if ($owner) {
            $owner->revokePermissionTo($permissions);
        }

        $kasir = Role::where('name', 'kasir')->where('guard_name', 'web')->first();
        if ($kasir) {
            $kasir->revokePermissionTo('view_setoran_harian');
        }

        Permission::whereIn('name', $permissions)->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
