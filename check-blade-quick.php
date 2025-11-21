<?php

/**
 * Verificação rápida de sintaxe Blade para arquivos específicos
 */

function checkBladeFile($filePath) {
    if (!file_exists($filePath)) {
        return ['error' => 'Arquivo não encontrado'];
    }
    
    $content = file_get_contents($filePath);
    $lines = explode("\n", $content);
    
    $stack = [];
    $errors = [];
    
    // Mapeamento de diretivas
    $openings = [
        'if' => 'endif',
        'foreach' => 'endforeach',
        'forelse' => 'endforelse',
        'while' => 'endwhile',
        'for' => 'endfor',
        'php' => 'endphp',
        'section' => 'endsection',
        'push' => 'endpush',
        'can' => 'endcan',
        'cannot' => 'endcannot',
    ];
    
    foreach ($lines as $num => $line) {
        $lineNum = $num + 1;
        
        // Encontrar aberturas
        if (preg_match_all('/@(\w+)\s*(?:\([^)]*\))?/i', $line, $matches)) {
            foreach ($matches[1] as $directive) {
                $dir = strtolower($directive);
                if (isset($openings[$dir])) {
                    $stack[] = [
                        'dir' => $dir,
                        'close' => $openings[$dir],
                        'line' => $lineNum,
                        'content' => trim($line)
                    ];
                } elseif ($dir === 'elseif' || $dir === 'else') {
                    // Verificar se há um @if na pilha
                    $hasIf = false;
                    foreach (array_reverse($stack) as $item) {
                        if ($item['dir'] === 'if') {
                            $hasIf = true;
                            break;
                        }
                        if (!in_array($item['dir'], ['elseif', 'else'])) {
                            break;
                        }
                    }
                    if (!$hasIf) {
                        $errors[] = "Linha $lineNum: @$directive sem @if correspondente";
                    }
                }
            }
        }
        
        // Encontrar fechamentos
        if (preg_match_all('/@(end\w+)\b/i', $line, $matches)) {
            foreach ($matches[1] as $closing) {
                $close = strtolower($closing);
                $found = false;
                $lastIndex = -1;
                
                for ($i = count($stack) - 1; $i >= 0; $i--) {
                    if ($stack[$i]['close'] === $close) {
                        $lastIndex = $i;
                        $found = true;
                        break;
                    }
                }
                
                if ($found) {
                    array_splice($stack, $lastIndex);
                } else {
                    $errors[] = "Linha $lineNum: @$closing sem abertura correspondente";
                }
            }
        }
    }
    
    // Verificar pilha restante
    foreach ($stack as $item) {
        $errors[] = "Linha {$item['line']}: @{$item['dir']} não fechado (esperando @{$item['close']})";
    }
    
    return $errors;
}

// Verificar arquivos específicos
$filesToCheck = [
    'resources/views/projects/index.blade.php',
];

echo "🔍 Verificando arquivos específicos...\n\n";

$hasErrors = false;

foreach ($filesToCheck as $file) {
    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $file;
    echo "📄 Verificando: $file\n";
    
    $errors = checkBladeFile($fullPath);
    
    if (is_array($errors) && !empty($errors)) {
        $hasErrors = true;
        echo "❌ Erros encontrados:\n";
        foreach ($errors as $error) {
            echo "   - $error\n";
        }
    } else {
        echo "✅ OK\n";
    }
    echo "\n";
}

if (!$hasErrors) {
    echo "✅ Nenhum erro encontrado nos arquivos verificados!\n";
} else {
    echo "❌ Erros encontrados. Por favor, corrija-os.\n";
    exit(1);
}

exit(0);

