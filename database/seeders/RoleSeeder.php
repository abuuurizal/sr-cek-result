<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'menu.dashboard',
            'menu.events',
            'menu.test-template',
            'menu.users',
            'menu.roles',
            'menu.site-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'super-admin']);
        // super-admin gets all permissions (also bypassed via Gate::before)
        $superAdmin->givePermissionTo($permissions);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(['menu.dashboard']);
    }
}
