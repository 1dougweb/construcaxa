<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GrantRole extends Command
{
    protected $signature = 'app:grant-role {email} {role}';
    protected $description = 'Grant a role to a user by email';

    public function handle(): int
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error('User not found: '.$email);
            return self::FAILURE;
        }

        try {
            $user->assignRole($role);
        } catch (\Throwable $e) {
            $this->error('Failed to assign role: '.$e->getMessage());
            return self::FAILURE;
        }

        $this->info("Role '{$role}' assigned to {$email}");
        return self::SUCCESS;
    }
}


