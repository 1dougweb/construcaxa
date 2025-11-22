<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        // Criar permissões
        $permissions = [
            'view dashboard',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view service-orders',
            'create service-orders',
            'edit service-orders',
            'delete service-orders',
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'view reports',
            'manage stock',
            'view attendance',
            'manage attendance',
            'view permissions',
            'manage permissions',
            // Projetos/Obras
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'post project-updates',
            'view client-projects',
            'view budgets',
            'manage budgets',
            'manage finances',
            'manage services',
            // Clientes
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',
            // Contratos
            'view contracts',
            'create contracts',
            'edit contracts',
            'delete contracts',
            // Vistorias
            'view inspections',
            'create inspections',
            'edit inspections',
            'delete inspections',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Criar papéis
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);
        $clientRole = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);

        // Atribuir permissões aos papéis
        $adminRole->givePermissionTo($permissions);
        
        $managerRole->givePermissionTo([
            'view dashboard',
            'view products',
            'create products',
            'edit products',
            'view categories',
            'create categories',
            'edit categories',
            'view service-orders',
            'create service-orders',
            'edit service-orders',
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'view employees',
            'create employees',
            'edit employees',
            'view reports',
            'manage stock',
            'view attendance',
            'manage attendance',
            'view permissions',
            'manage permissions',
            // Projetos/Obras
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'post project-updates',
            'view client-projects',
            'view budgets',
            'manage budgets',
            'manage finances',
            'manage services',
            // Clientes
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',
            // Contratos
            'view contracts',
            'create contracts',
            'edit contracts',
            'delete contracts',
            // Vistorias
            'view inspections',
            'create inspections',
            'edit inspections',
            'delete inspections',
        ]);

        $employeeRole->givePermissionTo([
            'view dashboard',
            'view products',
            'view categories',
            'view service-orders',
            'create service-orders',
            'view suppliers',
            'manage stock',
            'view attendance',
            // Projetos/Obras
            'view projects',
            'post project-updates',
        ]);

        // Cliente: permissões mínimas
        $clientRole->syncPermissions([
            'view client-projects',
            'view contracts', // Cliente pode ver seus próprios contratos
        ]);

        // Criar usuário admin
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Reset cache again after changes
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Criar categorias iniciais
        $categories = [
            [
                'name' => 'Adesivos',
                'description' => 'Materiais adesivos para comunicação visual',
                'sku_prefix' => 'ADV',
                'attributes_schema' => json_encode([
                    'material' => ['Vinil', 'Papel'],
                    'acabamento' => ['Brilho', 'Fosco'],
                ]),
            ],
            [
                'name' => 'Banners',
                'description' => 'Banners e faixas para comunicação visual',
                'sku_prefix' => 'BNR',
                'attributes_schema' => json_encode([
                    'material' => ['Lona', 'Tecido'],
                    'acabamento' => ['Ilhós', 'Bastão', 'Sem acabamento'],
                ]),
            ],
            [
                'name' => 'Placas',
                'description' => 'Placas para sinalização e comunicação visual',
                'sku_prefix' => 'PLC',
                'attributes_schema' => json_encode([
                    'material' => ['ACM', 'PVC', 'PS'],
                    'acabamento' => ['Com instalação', 'Sem instalação'],
                ]),
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
