<?php

/**
 * Script para limpar todos os caches e recompilar views
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧹 Limpando todos os caches...\n\n";

// Limpar cache de views
$commands = [
    'view:clear',
    'cache:clear',
    'config:clear',
    'route:clear',
];

foreach ($commands as $cmd) {
    echo "Executando: php artisan $cmd\n";
    Artisan::call($cmd);
    echo Artisan::output();
}

// Limpar diretório de views compiladas manualmente
$compiledViewsPath = storage_path('framework/views');
if (is_dir($compiledViewsPath)) {
    echo "🗑️  Limpando views compiladas...\n";
    $files = glob($compiledViewsPath . '/*.php');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $count++;
        }
    }
    echo "✅ Removidos $count arquivos compilados\n\n";
}

echo "✅ Limpeza concluída!\n";
echo "💡 Dica: Tente acessar a rota /files novamente.\n";
echo "💡 Se o erro persistir, verifique os logs em storage/logs/laravel.log\n";

