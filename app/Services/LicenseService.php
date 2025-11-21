<?php

namespace App\Services;

use App\Models\License;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class LicenseService
{
    protected $cacheKey = 'license_validation';
    protected $cacheTtl = 3600; // 1 hour

    /**
     * Validate license with the license server
     */
    public function validate(?string $token = null, ?string $serverUrl = null): array
    {
        $license = License::current();

        if (!$license) {
            return [
                'valid' => false,
                'message' => 'Licença não configurada',
                'error' => 'NO_LICENSE',
            ];
        }

        $token = $token ?? $license->license_token;
        
        // ============================================
        // CONFIGURAÇÃO DA URL DA API E API KEY
        // ============================================
        // Configure a URL da API do servidor de licenças:
        // Opção 1: Via .env (recomendado)
        //   Adicione no .env: LICENSE_SERVER_URL=https://seu-servidor.com
        //   Adicione no .env: LICENSE_API_KEY=sua-chave-api
        //
        // Opção 2: Diretamente no código (substitua os valores abaixo)
        //   $serverUrl = 'https://seu-servidor-de-licencas.com';
        //   $apiKey = 'sua-chave-api-aqui';
        // ============================================
        
        // URL do servidor de licenças - configure aqui ou no .env
        $serverUrl = $serverUrl ?? env('LICENSE_SERVER_URL', 'https://automacao-license-server.qiqivn.easypanel.host');
        // Se preferir colocar diretamente, descomente e configure:
        // $serverUrl = 'https://seu-servidor-de-licencas.com';
        
        // ============================================
        // CONFIGURAÇÃO DA API KEY
        // ============================================
        // IMPORTANTE: Configure a API key aqui ou no .env
        // 
        // Opção 1: Via .env (recomendado)
        //   Adicione no .env: LICENSE_API_KEY=sua-chave-api-aqui
        //   Depois execute: php artisan config:clear
        //
        // Opção 2: Diretamente no código (linha abaixo)
        //   Descomente a linha e coloque sua chave:
        // ============================================
        // ============================================
        // CONFIGURAÇÃO DA API KEY - PRODUÇÃO
        // ============================================
        // IMPORTANTE: A API key DEVE ser gerada no servidor de licenças
        // Formato: ls_ + 48 caracteres aleatórios (total: 51 caracteres)
        //
        // PASSO 1: No servidor de licenças, gere a API key:
        //   cd C:\Users\Douglas\Desktop\license-server
        //   php artisan api-key:generate "Stock Master Production"
        //
        // PASSO 2: Copie a API key gerada (começa com "ls_")
        //
        // PASSO 3: Configure no .env do projeto:
        //   LICENSE_API_KEY=ls_xxxxxxxxxxxxx... (chave completa de 51 caracteres)
        //
        // OU configure diretamente abaixo (linha 95) - NÃO RECOMENDADO PARA PRODUÇÃO
        // ============================================
        $apiKey = env('LICENSE_API_KEY', '');
        
        // Se preferir colocar diretamente no código (NÃO RECOMENDADO):
        // $apiKey = 'ls_COLE_SUA_API_KEY_COMPLETA_AQUI_51_CARACTERES';
        
        // Validar formato da API key
        if (!empty($apiKey) && $apiKey !== 'sua-chave-api-aqui') {
            $apiKey = trim($apiKey);
            // API keys válidas começam com "ls_" e têm 51 caracteres
            if (!str_starts_with($apiKey, 'ls_')) {
                Log::error('API Key com formato inválido - não começa com "ls_"', [
                    'preview' => substr($apiKey, 0, 10) . '...',
                    'length' => strlen($apiKey),
                    'hint' => 'API keys válidas começam com "ls_" e têm 51 caracteres. Gere uma nova no servidor de licenças',
                ]);
            } elseif (strlen($apiKey) !== 51) {
                Log::warning('API Key com tamanho incorreto', [
                    'length' => strlen($apiKey),
                    'expected' => 51,
                    'hint' => 'API keys válidas têm exatamente 51 caracteres (ls_ + 48 chars)',
                ]);
            }
        }
        
        // Log para debug
        if (empty($apiKey) || $apiKey === 'sua-chave-api-aqui') {
            Log::error('API Key não configurada - validação de licença falhará', [
                'hint' => 'Configure LICENSE_API_KEY no .env ou diretamente no código (linha 95)',
            ]);
        } else {
            Log::debug('API Key configurada', [
                'preview' => substr($apiKey, 0, 8) . '...' . substr($apiKey, -4),
                'length' => strlen($apiKey),
                'format_valid' => str_starts_with($apiKey, 'ls_') && strlen($apiKey) === 51,
            ]);
        }

        if (!$token || !$serverUrl) {
            return [
                'valid' => false,
                'message' => 'Token ou URL do servidor de licença não configurados',
                'error' => 'MISSING_CONFIG',
            ];
        }

        // Check cache first (only if not forcing validation)
        $cached = Cache::get($this->cacheKey);
        if ($cached !== null && !request()->has('force')) {
            return $cached;
        }

        try {
            $deviceId = License::generateDeviceId();
            $domain = request()->getHost();

            // Dados da requisição conforme esperado pelo servidor de licenças
            $requestData = [
                'token' => $token,
                'domain' => $domain,
                'device_id' => $deviceId,
            ];

            // Garantir que a URL não termina com / e adicionar o endpoint
            $serverUrl = rtrim($serverUrl, '/');
            $fullUrl = $serverUrl . '/api/v1/license/validate';
            
            // Preparar headers - API key deve ir no header, não no body
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];
            
            // Adicionar API key no header conforme esperado pelo servidor
            // O servidor aceita X-API-Key ou Authorization: Bearer {key}
            // IMPORTANTE: A API key deve começar com "ls_" e ter 51 caracteres (ls_ + 48 chars)
            if ($apiKey) {
                // Remover espaços em branco que possam ter sido adicionados acidentalmente
                $apiKey = trim($apiKey);
                
                // Verificar formato básico da chave
                if (!str_starts_with($apiKey, 'ls_')) {
                    Log::error('API Key não começa com "ls_" - formato inválido', [
                        'preview' => substr($apiKey, 0, 10) . '...',
                        'length' => strlen($apiKey),
                        'hint' => 'API keys válidas começam com "ls_" e têm 51 caracteres',
                    ]);
                }
                
                // Enviar no header X-API-Key (formato esperado pelo servidor)
                $headers['X-API-Key'] = $apiKey;
                
                Log::debug('API Key sendo enviada', [
                    'header' => 'X-API-Key',
                    'preview' => substr($apiKey, 0, 8) . '...' . substr($apiKey, -4),
                    'length' => strlen($apiKey),
                    'starts_with_ls' => str_starts_with($apiKey, 'ls_'),
                ]);
            } else {
                Log::error('API Key não configurada - requisição será rejeitada', [
                    'hint' => 'Configure LICENSE_API_KEY no .env ou no código',
                ]);
            }
            
            Log::info('Iniciando validação de licença', [
                'url' => $fullUrl,
                'server_url' => $serverUrl,
                'has_api_key' => !empty($apiKey),
                'api_key_preview' => $apiKey ? (substr($apiKey, 0, 8) . '...' . substr($apiKey, -4)) : 'NÃO CONFIGURADA',
                'token_preview' => substr($token, 0, 10) . '...',
                'domain' => $domain,
                'device_id' => $deviceId,
                'request_data' => $requestData,
                'headers' => array_keys($headers),
            ]);

            try {
                // Log detalhado antes da requisição
                Log::info('Enviando requisição para servidor de licenças', [
                    'url' => $fullUrl,
                    'method' => 'POST',
                    'headers' => array_merge($headers, ['X-API-Key' => $apiKey ? (substr($apiKey, 0, 8) . '...' . substr($apiKey, -4)) : 'NÃO CONFIGURADA']),
                    'request_data' => $requestData,
                    'api_key_length' => $apiKey ? strlen($apiKey) : 0,
                    'api_key_starts_with_ls' => $apiKey ? str_starts_with($apiKey, 'ls_') : false,
                ]);
                
                $response = Http::timeout(15)
                    ->retry(2, 100) // Tentar 2 vezes com delay de 100ms
                    ->withHeaders($headers)
                    ->post($fullUrl, $requestData);
                
                Log::info('Resposta recebida do servidor', [
                    'status' => $response->status(),
                    'successful' => $response->successful(),
                    'body_preview' => substr($response->body(), 0, 200),
                ]);
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Log::error('Erro de conexão com servidor de licenças', [
                    'url' => $fullUrl,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                throw new \Exception('Não foi possível conectar ao servidor de licenças: ' . $e->getMessage());
            } catch (\Exception $e) {
                Log::error('Erro inesperado ao fazer requisição', [
                    'url' => $fullUrl,
                    'error' => $e->getMessage(),
                    'class' => get_class($e),
                    'trace' => $e->getTraceAsString(),
                ]);
                throw $e;
            }

            if (!$response->successful()) {
                $errorBody = $response->body();
                $errorJson = $response->json();
                
                Log::error('Erro na validação de licença', [
                    'status' => $response->status(),
                    'body' => $errorBody,
                    'json' => $errorJson,
                    'url' => $fullUrl,
                    'headers_sent' => array_keys($headers),
                    'api_key_configured' => !empty($apiKey),
                    'api_key_preview' => $apiKey ? (substr($apiKey, 0, 8) . '...' . substr($apiKey, -4)) : 'NÃO CONFIGURADA',
                ]);
                
                $errorMessage = $errorJson['message'] ?? $errorJson['error'] ?? $errorBody;
                
                // Mensagem mais clara para erro 401 (API key inválida)
                if ($response->status() === 401) {
                    if (empty($apiKey) || $apiKey === 'sua-chave-api-aqui') {
                        $errorMessage = 'API key não configurada. Gere uma API key no servidor de licenças com: php artisan api-key:generate "Nome" e configure LICENSE_API_KEY no arquivo .env ou diretamente no código (app/Services/LicenseService.php linha 66).';
                    } else {
                        $keyPreview = substr($apiKey, 0, 10) . '...';
                        $isValidFormat = str_starts_with($apiKey, 'ls_');
                        $errorMessage = 'API key inválida, expirada ou desativada. ';
                        if (!$isValidFormat) {
                            $errorMessage .= 'A chave configurada (' . $keyPreview . ') não está no formato correto. API keys válidas começam com "ls_" e têm 51 caracteres. ';
                        }
                        $errorMessage .= 'Gere uma nova API key no servidor de licenças e configure corretamente.';
                    }
                }
                
                throw new \Exception('Erro ao comunicar com o servidor de licença (Status: ' . $response->status() . '): ' . $errorMessage);
            }

            $data = $response->json();
            
            if (!isset($data['valid'])) {
                Log::warning('Resposta inválida do servidor', [
                    'data' => $data,
                ]);
                throw new \Exception('Resposta inválida do servidor de licenças');
            }
            
            Log::info('Resposta da validação', [
                'valid' => $data['valid'],
                'message' => $data['message'] ?? 'Sem mensagem',
                'has_license_data' => isset($data['license']),
            ]);

            $result = [
                'valid' => $data['valid'] ?? false,
                'message' => $data['message'] ?? 'Erro desconhecido',
                'error' => $data['valid'] ? null : 'VALIDATION_FAILED',
                'license_data' => $data['license'] ?? null,
            ];

            // Update license record
            $license->is_valid = $result['valid'];
            $license->last_validated_at = now();
            $license->validation_error = $result['valid'] ? null : $result['message'];
            $license->license_data = $result['license_data'];
            
            if ($result['license_data'] && isset($result['license_data']['expires_at'])) {
                $license->expires_at = $result['license_data']['expires_at'] 
                    ? Carbon::parse($result['license_data']['expires_at']) 
                    : null;
            }

            $license->device_id = $deviceId;
            $license->domain = $domain;
            $license->save();

            // Cache the result
            Cache::put($this->cacheKey, $result, $this->cacheTtl);

            return $result;

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error('License validation error', [
                'error' => $errorMessage,
                'token_preview' => substr($token, 0, 10) . '...',
                'server_url' => $serverUrl,
                'has_api_key' => !empty($apiKey),
                'trace' => $e->getTraceAsString(),
            ]);

            // If we have a cached valid license, use it temporarily
            if ($license->is_valid && $license->last_validated_at && $license->last_validated_at->gt(now()->subHours(48))) {
                return [
                    'valid' => true,
                    'message' => 'Licença válida (modo offline)',
                    'error' => null,
                    'license_data' => $license->license_data,
                ];
            }

            return [
                'valid' => false,
                'message' => 'Erro ao validar licença: ' . $e->getMessage(),
                'error' => 'NETWORK_ERROR',
            ];
        }
    }

    /**
     * Check if license is currently valid
     */
    public function isLicenseValid(): bool
    {
        $license = License::current();
        
        if (!$license) {
            return false;
        }

        // Quick check from database
        if (!$license->isValid()) {
            // Try to validate
            $result = $this->validate();
            return $result['valid'] ?? false;
        }

        return true;
    }

    /**
     * Clear license cache
     */
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }

    /**
     * Store license configuration
     */
    public function store(string $token, ?string $serverUrl = null): bool
    {
        $license = License::current() ?? new License();
        
        $license->license_token = $token;
        // URL vem do código (.env ou diretamente configurada)
        $license->license_server_url = $serverUrl ?? env('LICENSE_SERVER_URL', 'https://seu-servidor-de-licencas.com');
        $license->save();

        // Clear cache and validate
        $this->clearCache();
        $result = $this->validate($token, null); // null para usar a URL do código

        return $result['valid'] ?? false;
    }

    /**
     * Get license status for real-time validation
     */
    public function getStatus(): array
    {
        $license = License::current();
        
        if (!$license) {
            return [
                'valid' => false,
                'message' => 'Licença não configurada',
                'configured' => false,
                'error' => 'NO_LICENSE',
            ];
        }

        $isValid = $license->isValid();
        $serverUrl = env('LICENSE_SERVER_URL', 'https://automacao-license-server.qiqivn.easypanel.host');
        
        return [
            'valid' => $isValid,
            'message' => $isValid 
                ? 'Licença válida' 
                : ($license->validation_error ?? 'Licença inválida'),
            'configured' => true,
            'expires_at' => $license->expires_at?->toIso8601String(),
            'last_validated_at' => $license->last_validated_at?->toIso8601String(),
            'server_url' => $serverUrl,
            'has_token' => !empty($license->license_token),
        ];
    }
}

