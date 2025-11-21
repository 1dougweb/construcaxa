# Scripts de Verificação de Sintaxe Blade

Este diretório contém scripts para verificar e corrigir problemas de sintaxe em templates Blade.

## Scripts Disponíveis

### 1. `check-blade-syntax.php`
Verifica o balanceamento de todas as diretivas Blade (@if/@endif, @foreach/@endforeach, etc.) em todos os arquivos `.blade.php`.

**Uso:**
```bash
php check-blade-syntax.php
```

**O que faz:**
- Verifica se todas as diretivas de abertura têm seus fechamentos correspondentes
- Detecta @elseif e @else sem @if correspondente
- Verifica blocos @php/@endphp
- Ignora diretivas dentro de strings

### 2. `check-blade-quick.php`
Verificação rápida de arquivos específicos relacionados à rota `/files`.

**Uso:**
```bash
php check-blade-quick.php
```

**Arquivos verificados:**
- `resources/views/projects/index.blade.php`

### 3. `check-blade-compile.php`
Usa o compilador real do Laravel para compilar todas as views e detectar erros de sintaxe.

**Uso:**
```bash
php check-blade-compile.php
```

**Nota:** Este script pode ser lento pois compila todas as views.

### 4. `fix-blade-cache.php`
Limpa todos os caches do Laravel e remove views compiladas.

**Uso:**
```bash
php fix-blade-cache.php
```

**O que faz:**
- Limpa cache de views (`php artisan view:clear`)
- Limpa cache da aplicação (`php artisan cache:clear`)
- Limpa cache de configuração (`php artisan config:clear`)
- Limpa cache de rotas (`php artisan route:clear`)
- Remove manualmente arquivos compilados de `storage/framework/views/`

## Solução de Problemas

### Erro: "syntax error, unexpected end of file, expecting 'elseif' or 'else' or 'endif'"

1. **Execute o verificador de sintaxe:**
   ```bash
   php check-blade-syntax.php
   ```

2. **Limpe todos os caches:**
   ```bash
   php fix-blade-cache.php
   ```

3. **Verifique os logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Se o erro persistir:**
   - Verifique se há arquivos compilados antigos em `storage/framework/views/`
   - Verifique se há problemas de encoding nos arquivos Blade
   - Verifique se há diretivas Blade dentro de strings JavaScript/Alpine.js que estão sendo interpretadas incorretamente

### Diretivas Blade Comuns

| Abertura | Fechamento |
|----------|------------|
| `@if` | `@endif` |
| `@foreach` | `@endforeach` |
| `@forelse` | `@endforelse` |
| `@while` | `@endwhile` |
| `@for` | `@endfor` |
| `@php` | `@endphp` |
| `@section` | `@endsection` |
| `@can` | `@endcan` |

### Notas Importantes

- `@elseif` e `@else` não têm fechamentos próprios, mas precisam estar dentro de um bloco `@if`
- `@empty` faz parte de `@forelse` e não precisa de fechamento separado
- Diretivas dentro de strings JavaScript (como em atributos Alpine.js) são ignoradas pelo verificador

## Correções Aplicadas

### Arquivo: `resources/views/livewire/file-manager.blade.php`

**Problema:** Condição complexa com `isset()` e acesso a array dentro de `@if`.

**Solução:** Extraído para um bloco `@php` antes do `@if`:

```blade
@php
    $displayType = $fileData[$file->id]['displayType'] ?? 'generic';
@endphp
@if($displayType === 'image')
    ...
@elseif($displayType === 'pdf')
    ...
@else
    ...
@endif
```

### Arquivo: `resources/views/projects/index.blade.php`

**Problema:** Estrutura de divs corrigida para melhor organização.

## Como Usar os Scripts em Produção

1. Execute primeiro `check-blade-syntax.php` para verificar sintaxe
2. Se houver erros, corrija-os manualmente
3. Execute `fix-blade-cache.php` para limpar caches
4. Teste a aplicação
5. Se o problema persistir, execute `check-blade-compile.php` para verificação mais profunda

## Contribuindo

Se encontrar novos tipos de erros que os scripts não detectam, adicione as verificações apropriadas nos scripts.

