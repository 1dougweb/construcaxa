<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoogleMapsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add default Google Maps settings
        Setting::updateOrCreate(
            ['key' => 'google_maps_api_key'],
            [
                'value' => '',
                'type' => 'string',
                'description' => 'Chave da API do Google Maps para exibição de mapas'
            ]
        );
    }
}
