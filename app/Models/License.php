<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class License extends Model
{
    protected $fillable = [
        'license_token',
        'license_server_url',
        'device_id',
        'domain',
        'is_valid',
        'last_validated_at',
        'expires_at',
        'validation_error',
        'license_data',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'last_validated_at' => 'datetime',
        'expires_at' => 'datetime',
        'license_data' => 'array',
    ];

    /**
     * Get the current license instance (singleton)
     */
    public static function current(): ?self
    {
        return static::first();
    }

    /**
     * Check if license is valid and not expired
     */
    public function isValid(): bool
    {
        if (!$this->is_valid) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // Consider license invalid if not validated in the last 24 hours
        if ($this->last_validated_at && $this->last_validated_at->lt(Carbon::now()->subHours(24))) {
            return false;
        }

        return true;
    }

    /**
     * Generate a unique device ID
     */
    public static function generateDeviceId(): string
    {
        $deviceId = session('device_id');
        
        if (!$deviceId) {
            $deviceId = md5(request()->ip() . request()->userAgent() . config('app.key'));
            session(['device_id' => $deviceId]);
        }

        return $deviceId;
    }
}
