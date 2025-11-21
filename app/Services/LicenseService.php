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
        
        // API Key para autenticação - configure aqui ou no .env
        $apiKey = env('LICENSE_API_KEY', '');
        // Se preferir colocar diretamente, descomente e configure:
        // $apiKey = 'sua-chave-api-aqui';
        
        // Log para debug
        if (empty($apiKey)) {
            Log::warning('API Key não configurada - a validação pode falhar', [
                'hint' => 'Configure LICENSE_API_KEY no .env ou diretamente no código (linha 53)',
            ]);
        }
        
        // Log para debug (mostra apenas parte da chave por segurança)
        if (empty($apiKey)) {
            Log::warning('API Key não configurada', [
                'hint' => 'Configure LICENSE_API_KEY no .env ou diretamente no código (linha 53)',
            ]);
        } else {
            Log::debug('API Key configurada', [
                'preview' => substr($apiKey, 0, 8) . '...' . substr($apiKey, -4),
                'length' => strlen($apiKey),
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
            $fullUrl = $serverUrl . '/api/license/validate';
            
            // Preparar headers - API key deve ir no header, não no body
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];
            
            // Adicionar API key no header conforme esperado pelo servidor
            // O servidor aceita X-API-Key ou Authorization: Bearer {key}
            if ($apiKey) {
                $headers['X-API-Key'] = $apiKey;
                // Alternativa: usar Authorization Bearer
                // $headers['Authorization'] = 'Bearer ' . $apiKey;
            }
            
            Log::info('Validando licença', [
                'url' => $fullUrl,
                'server_url' => $serverUrl,
                'has_api_key' => !empty($apiKey),
                'token_preview' => substr($token, 0, 10) . '...',
                'domain' => $domain,
                'device_id' => $deviceId,
            ]);

            $response = Http::timeout(10)
                ->withHeaders($headers)
                ->post($fullUrl, $requestData);

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
                    if (empty($apiKey)) {
                        $errorMessage = 'API key não configurada. Configure LICENSE_API_KEY no arquivo .env ou diretamente no código (app/Services/LicenseService.php linha 53).';
                    } else {
                        $errorMessage = 'API key inválida, expirada ou desativada. Verifique se a chave está correta no arquivo .env (LICENSE_API_KEY) ou no código.';
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

