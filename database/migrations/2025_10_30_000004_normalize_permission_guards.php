<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('roles')->update(['guard_name' => 'web']);
        DB::table('permissions')->update(['guard_name' => 'web']);
    }

    public function down(): void
    {
        // no-op: cannot safely restore previous guard_name values
    }
};


