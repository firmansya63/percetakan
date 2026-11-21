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

        $kasir = Role::where('name', 'kasir')->where('guard_name', 'web')->first();

        if ($kasir) {
            $kasir->givePermissionTo('create_cashflow');
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $kasir = Role::where('name', 'kasir')->where('guard_name', 'web')->first();

        if ($kasir) {
            $kasir->revokePermissionTo('create_cashflow');
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
