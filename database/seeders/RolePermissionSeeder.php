<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Basic permissions
        $permissions = [
            'view dashboard',
            'manage products',
            'manage categories',
            'manage suppliers',
            'manage employees',
            'manage reports',
            'manage finances',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $operator = Role::firstOrCreate(['name' => 'operator']);

        // Assign permissions
        $admin->syncPermissions(Permission::all());
        $manager->syncPermissions([
            'view dashboard',
            'manage products',
            'manage categories',
            'manage suppliers',
            'manage reports',
            'manage finances',
        ]);
        $operator->syncPermissions([
            'view dashboard',
        ]);
    }
}


