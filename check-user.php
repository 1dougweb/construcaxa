<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// Encontrar João Silva
$joao = User::where('name', 'João Silva')->first();

if ($joao) {
    echo "User: {$joao->name} ({$joao->email})\n";
    echo "Roles: " . $joao->roles->pluck('name')->join(', ') . "\n";
    echo "All Permissions:\n";
    foreach ($joao->getAllPermissions() as $perm) {
        echo "- {$perm->name}\n";
    }
    
    echo "\nSpecific checks:\n";
    echo "Can create products: " . ($joao->can('create products') ? 'YES' : 'NO') . "\n";
    echo "Can edit products: " . ($joao->can('edit products') ? 'YES' : 'NO') . "\n";
    echo "Can delete products: " . ($joao->can('delete products') ? 'YES' : 'NO') . "\n";
} else {
    echo "João Silva not found\n";
}
