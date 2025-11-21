<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GeneratePwaIcons extends Command
{
    protected $signature = 'pwa:generate-icons';
    protected $description = 'Gera instruções para criar ícones PWA a partir do logo SVG';

    public function handle()
    {
        $this->info('Gerando instruções para criação de ícones PWA...');
        
        $iconSizes = [72, 96, 128, 144, 152, 192, 384, 512];
        $logoPath = public_path('assets/images/logo.svg');
        
        if (!File::exists($logoPath)) {
            $this->error('Logo SVG não encontrado em: ' . $logoPath);
            return 1;
        }
        
        $this->info('Logo encontrado: ' . $logoPath);
        $this->info('');
        $this->info('Para gerar os ícones PWA, você pode:');
        $this->info('');
        $this->info('1. Usar uma ferramenta online como:');
        $this->info('   - https://realfavicongenerator.net/');
        $this->info('   - https://www.pwabuilder.com/imageGenerator');
        $this->info('   - https://favicon.io/favicon-converter/');
        $this->info('');
        $this->info('2. Usar ImageMagick via linha de comando:');
        foreach ($iconSizes as $size) {
            $this->info("   convert -background none -resize {$size}x{$size} public/assets/images/logo.svg public/icons/icon-{$size}x{$size}.png");
        }
        $this->info('');
        $this->info('3. Usar uma ferramenta de design (Figma, Photoshop, etc.) para exportar o logo nos tamanhos:');
        foreach ($iconSizes as $size) {
            $this->info("   - {$size}x{$size}px");
        }
        $this->info('');
        $this->info('Os ícones devem ser salvos em: public/icons/');
        $this->info('Formatos necessários: PNG com fundo transparente');
        $this->info('');
        $this->info('Tamanhos necessários:');
        foreach ($iconSizes as $size) {
            $iconPath = public_path("icons/icon-{$size}x{$size}.png");
            $exists = File::exists($iconPath) ? '✓' : '✗';
            $this->info("   {$exists} icon-{$size}x{$size}.png");
        }
        
        return 0;
    }
}


