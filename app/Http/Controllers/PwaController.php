<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class PwaController extends Controller
{
    public function manifest()
    {
        $iconSizes = [72, 96, 128, 144, 152, 192, 384, 512];
        $icons = [];

        foreach ($iconSizes as $size) {
            $iconPath = public_path("icons/icon-{$size}x{$size}.png");
            if (File::exists($iconPath)) {
                $icons[] = [
                    'src' => "/icons/icon-{$size}x{$size}.png",
                    'sizes' => "{$size}x{$size}",
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ];
            }
        }

        $manifest = [
            'name' => 'Stock Master',
            'short_name' => 'Stock Master',
            'description' => 'Sistema de gestão de estoque e projetos',
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#1E2780',
            'orientation' => 'portrait-primary',
            'scope' => '/',
            'icons' => $icons
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json');
    }
}


