<?php

/**
 * Script para compilar views Blade e detectar erros de sintaxe
 * Usa o compilador real do Laravel para encontrar problemas
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$errors = [];
$viewsPath = resource_path('views');

if (!is_dir($viewsPath)) {
    echo "❌ Diretório 'resources/views' não encontrado!\n";
    exit(1);
}

echo "🔍 Compilando views Blade para detectar erros de sintaxe...\n\n";

$finder = new \Symfony\Component\Finder\Finder();
$finder->files()
    ->in($viewsPath)
    ->name('*.blade.php');

$compiler = app('blade.compiler');

foreach ($finder as $file) {
    $relativePath = str_replace($viewsPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
    $relativePath = str_replace(DIRECTORY_SEPARATOR, '.', dirname($relativePath));
    $viewName = $relativePath . '.' . basename($file->getFilename(), '.blade.php');
    $viewName = str_replace('views.', '', $viewName);
    $viewName = str_replace('.', '/', $viewName);
    
    try {
        $content = file_get_contents($file->getPathname());
        
        // Tentar compilar a view
        $compiled = $compiler->compileString($content);
        
        // Tentar compilar como PHP para verificar sintaxe
        $phpCode = $compiler->compileString($content);
        
        // Verificar sintaxe PHP
        $output = [];
        $returnVar = 0;
        $tmpFile = tempnam(sys_get_temp_dir(), 'blade_check_');
        file_put_contents($tmpFile, '<?php ' . $phpCode);
        
        exec("php -l \"$tmpFile\" 2>&1", $output, $returnVar);
        unlink($tmpFile);
        
        if ($returnVar !== 0) {
            $errorMessage = implode("\n", $output);
            $errors[] = [
                'file' => $file->getPathname(),
                'view' => $viewName,
                'error' => $errorMessage
            ];
        }
    } catch (\Exception $e) {
        $errors[] = [
            'file' => $file->getPathname(),
            'view' => $viewName,
            'error' => $e->getMessage(),
            'type' => get_class($e)
        ];
    } catch (\Error $e) {
        $errors[] = [
            'file' => $file->getPathname(),
            'view' => $viewName,
            'error' => $e->getMessage(),
            'type' => get_class($e),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ];
    }
}

if (empty($errors)) {
    echo "✅ Nenhum erro encontrado! Todas as views compilam corretamente.\n";
    exit(0);
}

echo "❌ Encontrados " . count($errors) . " erro(s):\n\n";

foreach ($errors as $error) {
    $relativePath = str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $error['file']);
    echo "📄 Arquivo: {$relativePath}\n";
    if (isset($error['view'])) {
        echo "   View: {$error['view']}\n";
    }
    echo str_repeat('=', 80) . "\n";
    echo "Erro: {$error['error']}\n";
    
    if (isset($error['line'])) {
        echo "Linha: {$error['line']}\n";
    }
    
    if (isset($error['trace'])) {
        echo "\nStack trace:\n";
        echo $error['trace'] . "\n";
    }
    
    echo "\n";
}

exit(1);

