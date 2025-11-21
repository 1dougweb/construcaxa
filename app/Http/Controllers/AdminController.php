<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function settings()
    {
        $settings = [
            'google_maps_api_key' => Setting::get('google_maps_api_key', ''),
            'license_server_url' => Setting::get('license_server_url', env('LICENSE_SERVER_URL', '')),
            'license_api_key' => Setting::get('license_api_key', ''),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'google_maps_api_key' => 'nullable|string|max:255',
            'license_server_url' => 'nullable|url|max:255',
            'license_api_key' => 'nullable|string|max:255',
        ]);

        Setting::set(
            'google_maps_api_key', 
            $request->google_maps_api_key, 
            'string', 
            'Chave da API do Google Maps para exibição de mapas'
        );

        Setting::set(
            'license_server_url', 
            $request->license_server_url, 
            'string', 
            'URL do servidor de licenças'
        );

        Setting::set(
            'license_api_key', 
            $request->license_api_key, 
            'string', 
            'Chave da API para validação de licenças'
        );

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
