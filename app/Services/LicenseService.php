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

            $requestData = [
                'token' => $token,
                'domain' => $domain,
                'device_id' => $deviceId,
            ];

            // Add API key if configured
            if ($apiKey) {
                $requestData['api_key'] = $apiKey;
            }

            $response = Http::timeout(10)
                ->withHeaders($apiKey ? ['Authorization' => 'Bearer ' . $apiKey] : [])
                ->post($serverUrl . '/api/license/validate', $requestData);

            if (!$response->successful()) {
                throw new \Exception('Erro ao comunicar com o servidor de licença: ' . $response->status());
            }

            $data = $response->json();

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
            Log::error('License validation error', [
                'error' => $e->getMessage(),
                'token' => substr($token, 0, 10) . '...',
                'server_url' => $serverUrl,
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
            ];
        }

        $isValid = $license->isValid();
        
        return [
            'valid' => $isValid,
            'message' => $isValid 
                ? 'Licença válida' 
                : ($license->validation_error ?? 'Licença inválida'),
            'configured' => true,
            'expires_at' => $license->expires_at?->toIso8601String(),
            'last_validated_at' => $license->last_validated_at?->toIso8601String(),
        ];
    }
}

