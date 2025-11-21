<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrar usuários com role 'client' para a tabela clients
        $users = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'client')
            ->select('users.*')
            ->get();

        foreach ($users as $user) {
            DB::table('clients')->insert([
                'user_id' => $user->id,
                'type' => 'individual',
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'is_active' => true,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não há necessidade de reverter, pois os dados originais em users permanecem
    }
};
