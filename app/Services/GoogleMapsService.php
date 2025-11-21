<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleMapsService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://maps.googleapis.com/maps/api';

    public function __construct()
    {
        $this->apiKey = Setting::get('google_maps_api_key', '');
    }

    /**
     * Get coordinates (latitude, longitude) from an address
     */
    public function geocodeAddress(string $address): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning('Google Maps API key not configured');
            return null;
        }

        if (empty($address)) {
            return null;
        }

        try {
            $url = "{$this->baseUrl}/geocode/json";
            $params = [
                'address' => $address,
                'key' => $this->apiKey,
                'language' => 'pt-BR',
                'region' => 'BR'
            ];
            
            $response = Http::withOptions([
                'verify' => config('app.env') === 'production'
            ])->get($url, $params);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'OK' && !empty($data['results'])) {
                    $location = $data['results'][0]['geometry']['location'];
                    
                    return [
                        'latitude' => $location['lat'],
                        'longitude' => $location['lng'],
                        'formatted_address' => $data['results'][0]['formatted_address']
                    ];
                }
            }

            Log::warning('Geocoding failed', [
                'address' => $address,
                'status' => $data['status'] ?? 'unknown',
                'error_message' => $data['error_message'] ?? 'No error message'
            ]);

        } catch (\Exception $e) {
            Log::error('Geocoding error', [
                'address' => $address,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Get address from coordinates (reverse geocoding)
     */
    public function reverseGeocode(float $latitude, float $longitude): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('Google Maps API key not configured');
            return null;
        }

        try {
            $response = Http::withOptions([
                'verify' => config('app.env') === 'production'
            ])->get("{$this->baseUrl}/geocode/json", [
                'latlng' => "{$latitude},{$longitude}",
                'key' => $this->apiKey,
                'language' => 'pt-BR',
                'region' => 'BR'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'OK' && !empty($data['results'])) {
                    return $data['results'][0]['formatted_address'];
                }
            }

        } catch (\Exception $e) {
            Log::error('Reverse geocoding error', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Check if API key is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get the API key for frontend use
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
