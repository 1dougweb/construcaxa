<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
        // opcional: garantir papel admin se existir Spatie
        if (method_exists($user, 'assignRole')) {
            try { $user->assignRole('admin'); } catch (\Throwable $e) {}
        }
    }
}
