<?php

/**
 * Script para verificar o balanceamento de diretivas Blade
 * Verifica se todas as tags @if, @foreach, @forelse, etc. têm seus fechamentos correspondentes
 */

class BladeSyntaxChecker
{
    private array $directives = [
        'if' => 'endif',
        'elseif' => null, // Não precisa de fechamento, mas precisa vir depois de @if
        'else' => null,
        'foreach' => 'endforeach',
        'forelse' => 'endforelse',
        'while' => 'endwhile',
        'for' => 'endfor',
        'switch' => 'endswitch',
        'case' => null,
        'break' => null,
        'default' => null,
        'php' => 'endphp',
        'once' => 'endonce',
        'push' => 'endpush',
        'prepend' => 'endprepend',
        'section' => 'endsection',
        'slot' => 'endslot',
        'component' => 'endcomponent',
        'auth' => 'endauth',
        'guest' => 'endguest',
        'can' => 'endcan',
        'cannot' => 'endcannot',
        'canany' => 'endcanany',
        'empty' => null, // Parte do @forelse
        'continue' => null,
        'break' => null,
    ];

    private array $errors = [];

    public function checkDirectory(string $directory): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php' && str_contains($file->getFilename(), '.blade.php')) {
                $this->checkFile($file->getPathname());
            }
        }
    }

    public function checkFile(string $filePath): void
    {
        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);
        
        $stack = [];
        $lineNumber = 0;
        $inPhpBlock = false;
        $phpBlockStart = 0;

        foreach ($lines as $index => $line) {
            $lineNumber = $index + 1; // 1-based line numbers
            
            // Ignorar comentários Blade completos
            if (preg_match('/^\s*{{--.*--}}\s*$/', $line)) {
                continue;
            }

            // Verificar se estamos em um bloco PHP
            if (preg_match('/@php\b/i', $line)) {
                $inPhpBlock = true;
                $phpBlockStart = $lineNumber;
            }
            if (preg_match('/@endphp\b/i', $line)) {
                $inPhpBlock = false;
            }

            // Verificar diretivas de abertura (mais preciso)
            // Padrão: @directive ou @directive(...)
            if (preg_match_all('/@(\w+)(?:\s*\([^)]*\))?/i', $line, $matches, PREG_OFFSET_CAPTURE)) {
                foreach ($matches[1] as $match) {
                    $directive = strtolower($match[0]);
                    $offset = $match[1];
                    
                    // Verificar se não está dentro de uma string
                    $before = substr($line, 0, $offset);
                    $singleQuotes = substr_count($before, "'") - substr_count($before, "\\'");
                    $doubleQuotes = substr_count($before, '"') - substr_count($before, '\\"');
                    
                    // Se está dentro de uma string, ignorar
                    if (($singleQuotes % 2 !== 0 && !($doubleQuotes % 2 !== 0)) || 
                        ($doubleQuotes % 2 !== 0 && !($singleQuotes % 2 !== 0))) {
                        continue;
                    }
                    
                    if (isset($this->directives[$directive])) {
                        $closingDirective = $this->directives[$directive];
                        
                        // Se tem fechamento, adiciona à pilha
                        if ($closingDirective !== null) {
                            $stack[] = [
                                'directive' => $directive,
                                'closing' => $closingDirective,
                                'line' => $lineNumber,
                                'file' => $filePath,
                                'content' => trim($line)
                            ];
                        } elseif ($directive === 'elseif' || $directive === 'else') {
                            // Verificar se há um @if na pilha
                            $hasIf = false;
                            for ($i = count($stack) - 1; $i >= 0; $i--) {
                                if ($stack[$i]['directive'] === 'if') {
                                    $hasIf = true;
                                    break;
                                }
                                // Se encontramos outra diretiva que não é if, paramos
                                if (!in_array($stack[$i]['directive'], ['elseif', 'else'])) {
                                    break;
                                }
                            }
                            
                            if (!$hasIf) {
                                $this->errors[] = [
                                    'file' => $filePath,
                                    'line' => $lineNumber,
                                    'type' => 'orphan_else',
                                    'message' => "Directiva '@{$directive}' sem @if correspondente",
                                    'content' => trim($line)
                                ];
                            }
                        }
                    }
                }
            }

            // Verificar diretivas de fechamento
            if (preg_match_all('/@(endif|endforeach|endforelse|endwhile|endfor|endswitch|endphp|endonce|endpush|endprepend|endsection|endslot|endcomponent|endauth|endguest|endcan|endcannot|endcanany)\b/i', $line, $matches, PREG_OFFSET_CAPTURE)) {
                foreach ($matches[1] as $match) {
                    $closingDirective = strtolower($match[0]);
                    $offset = $match[1];
                    
                    // Verificar se não está dentro de uma string
                    $before = substr($line, 0, $offset);
                    $singleQuotes = substr_count($before, "'") - substr_count($before, "\\'");
                    $doubleQuotes = substr_count($before, '"') - substr_count($before, '\\"');
                    
                    if (($singleQuotes % 2 !== 0 && !($doubleQuotes % 2 !== 0)) || 
                        ($doubleQuotes % 2 !== 0 && !($singleQuotes % 2 !== 0))) {
                        continue;
                    }
                    
                    // Verificar se há correspondente na pilha
                    $found = false;
                    $matchedIndex = -1;
                    for ($i = count($stack) - 1; $i >= 0; $i--) {
                        if ($stack[$i]['closing'] === $closingDirective) {
                            $matchedIndex = $i;
                            $found = true;
                            break;
                        }
                    }
                    
                    if ($found) {
                        // Remover da pilha tudo até (e incluindo) o match
                        array_splice($stack, $matchedIndex);
                    } else {
                        $this->errors[] = [
                            'file' => $filePath,
                            'line' => $lineNumber,
                            'type' => 'unexpected_closing',
                            'message' => "Directiva de fechamento '@{$closingDirective}' sem correspondente de abertura",
                            'content' => trim($line)
                        ];
                    }
                }
            }
        }

        // Verificar se há diretivas não fechadas
        foreach ($stack as $item) {
            $this->errors[] = [
                'file' => $item['file'],
                'line' => $item['line'],
                'type' => 'unclosed',
                'message' => "Directiva '@{$item['directive']}' aberta na linha {$item['line']} não foi fechada (esperando '@{$item['closing']}')",
                'content' => $item['content']
            ];
        }

        // Verificar se há bloco PHP não fechado
        if ($inPhpBlock) {
            $this->errors[] = [
                'file' => $filePath,
                'line' => $phpBlockStart,
                'type' => 'unclosed_php',
                'message' => "Bloco @php aberto na linha {$phpBlockStart} não foi fechado com @endphp",
                'content' => ''
            ];
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function printReport(): void
    {
        if (empty($this->errors)) {
            echo "✅ Nenhum erro encontrado! Todas as diretivas Blade estão balanceadas.\n";
            return;
        }

        echo "❌ Encontrados " . count($this->errors) . " erro(s):\n\n";

        $files = [];
        foreach ($this->errors as $error) {
            $files[$error['file']][] = $error;
        }

        foreach ($files as $file => $errors) {
            $relativePath = str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $file);
            echo "📄 Arquivo: {$relativePath}\n";
            echo str_repeat('=', 80) . "\n";
            
            foreach ($errors as $error) {
                echo "  Linha {$error['line']}: {$error['message']}\n";
                if (!empty($error['content'])) {
                    echo "  Conteúdo: {$error['content']}\n";
                }
                echo "\n";
            }
            echo "\n";
        }
    }
}

// Executar verificação
echo "🔍 Verificando sintaxe Blade...\n\n";

$checker = new BladeSyntaxChecker();
$viewsDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';

if (!is_dir($viewsDirectory)) {
    echo "❌ Diretório 'resources/views' não encontrado!\n";
    exit(1);
}

$checker->checkDirectory($viewsDirectory);
$checker->printReport();

$errors = $checker->getErrors();
exit(empty($errors) ? 0 : 1);

