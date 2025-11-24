<?php

use Laravel\Reverb\Protocols\Pusher;

return [

    /*
    |--------------------------------------------------------------------------
    | Reverb Servers
    |--------------------------------------------------------------------------
    |
    | Here you may define how you want your Reverb servers to be configured.
    | You are free to configure multiple Reverb servers for your application
    | as well as their respective settings. See the documentation for more
    | information on what each of these settings mean.
    |
    */

    'servers' => [

        'reverb' => [
            // Extrair configurações do APP_URL se REVERB_* não estiverem definidas
            'host' => (function() {
                $host = env('REVERB_HOST');
                if (!$host && ($appUrl = env('APP_URL'))) {
                    $parsed = parse_url($appUrl);
                    $host = $parsed['host'] ?? null;
                }
                $host = $host ?: '127.0.0.1';
                return $host === 'localhost' ? '127.0.0.1' : $host;
            })(),
            'port' => (function() {
                $port = env('REVERB_PORT');
                if (!$port && ($appUrl = env('APP_URL'))) {
                    $parsed = parse_url($appUrl);
                    $port = $parsed['port'] ?? (($parsed['scheme'] ?? 'http') === 'https' ? 443 : 80);
                }
                return $port ?: 8080;
            })(),
            'hostname' => (function() {
                $hostname = env('REVERB_HOSTNAME') ?: env('REVERB_HOST');
                if (!$hostname && ($appUrl = env('APP_URL'))) {
                    $parsed = parse_url($appUrl);
                    $hostname = $parsed['host'] ?? null;
                }
                $hostname = $hostname ?: '127.0.0.1';
                return $hostname === 'localhost' ? '127.0.0.1' : $hostname;
            })(),
            'options' => [
                'scheme' => (function() {
                    $scheme = env('REVERB_SCHEME');
                    if (!$scheme && ($appUrl = env('APP_URL'))) {
                        $parsed = parse_url($appUrl);
                        $scheme = $parsed['scheme'] ?? null;
                    }
                    return $scheme ?: 'http';
                })(),
                'useTLS' => (function() {
                    $scheme = env('REVERB_SCHEME');
                    if (!$scheme && ($appUrl = env('APP_URL'))) {
                        $parsed = parse_url($appUrl);
                        $scheme = $parsed['scheme'] ?? null;
                    }
                    return ($scheme ?: 'http') === 'https';
                })(),
            ],
            'scaling' => [
                'enabled' => env('REVERB_SCALING_ENABLED', false),
                'channel' => env('REVERB_SCALING_CHANNEL', 'reverb_cluster'),
                'server' => env('REVERB_SCALING_SERVER', 'redis'),
            ],
            'pulse' => [
                'ingest' => [
                    'enabled' => env('REVERB_PULSE_INGEST_ENABLED', false),
                    'table' => env('REVERB_PULSE_INGEST_TABLE', 'pulse_entries'),
                ],
            ],
            'pulse_ingest_interval' => env('REVERB_PULSE_INGEST_INTERVAL', 15),
            'telescope_ingest_interval' => env('REVERB_TELESCOPE_INGEST_INTERVAL', 15),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Reverb Applications
    |--------------------------------------------------------------------------
    |
    | Here you may define how you want your Reverb applications to be
    | configured. You are free to configure multiple Reverb applications
    | for your application. See the documentation for more information.
    |
    */

    'apps' => [

        'provider' => 'config',

        'apps' => [
            'main' => [
                'app_id' => env('REVERB_APP_ID', 'stock-master'),
                'key' => env('REVERB_APP_KEY'),
                'secret' => env('REVERB_APP_SECRET'),
                'capacity' => null,
                'allowed_origins' => ['*'],
                'ping_interval' => env('REVERB_PING_INTERVAL', 30),
                'activity_timeout' => env('REVERB_ACTIVITY_TIMEOUT', 30),
                'max_message_size' => env('REVERB_MAX_MESSAGE_SIZE', 10_000),
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Reverb HTTP Request / Response
    |--------------------------------------------------------------------------
    |
    | Here you may configure the HTTP request / response handling for your
    | Reverb application. See the documentation for more information.
    |
    */

    'http' => [
        'request_limit' => env('REVERB_HTTP_REQUEST_LIMIT', 10_000),
        'request_interval' => env('REVERB_HTTP_REQUEST_INTERVAL', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Reverb Protocol
    |--------------------------------------------------------------------------
    |
    | Here you may configure the protocol that Reverb uses. By default, Reverb
    | uses the Pusher protocol, which is compatible with Laravel Echo and the
    | Pusher JavaScript SDK. See the documentation for more information.
    |
    */

    'protocol' => Pusher::class,

];

