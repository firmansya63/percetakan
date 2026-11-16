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
            'view_cashflow',
            'create_cashflow',
            'edit_cashflow',
            'delete_cashflow',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $owner = Role::findByName('owner');
        $owner->givePermissionTo($permissions);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_cashflow',
            'create_cashflow',
            'edit_cashflow',
            'delete_cashflow',
        ];

        $owner = Role::findByName('owner');
        if ($owner) {
            $owner->revokePermissionTo($permissions);
        }

        Permission::whereIn('name', $permissions)->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
