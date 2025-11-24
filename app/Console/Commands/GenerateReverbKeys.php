<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateReverbKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reverb:generate-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Reverb application keys';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating Reverb keys...');

        $appKey = Str::random(20);
        $appSecret = Str::random(40);

        $this->info('');
        $this->info('Add these to your .env file:');
        $this->info('');
        $this->line('REVERB_APP_ID=stock-master');
        $this->line('REVERB_APP_KEY=' . $appKey);
        $this->line('REVERB_APP_SECRET=' . $appSecret);
        $this->line('REVERB_HOST=127.0.0.1');
        $this->line('REVERB_PORT=8080');
        $this->line('REVERB_SCHEME=http');
        $this->line('REVERB_HOSTNAME=127.0.0.1');
        $this->info('');
        $this->info('And for Vite (frontend):');
        $this->info('');
        $this->line('VITE_REVERB_APP_KEY=' . $appKey);
        $this->line('VITE_REVERB_HOST=127.0.0.1');
        $this->line('VITE_REVERB_PORT=8080');
        $this->line('VITE_REVERB_SCHEME=http');
        $this->info('');

        // Tentar atualizar o .env automaticamente
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            
            // Verificar se as chaves já existem
            if (strpos($envContent, 'REVERB_APP_KEY=') === false) {
                $this->info('Attempting to add keys to .env file...');
                
                $newLines = "\n# Reverb Configuration\n";
                $newLines .= "BROADCAST_DRIVER=reverb\n";
                $newLines .= "REVERB_APP_ID=stock-master\n";
                $newLines .= "REVERB_APP_KEY={$appKey}\n";
                $newLines .= "REVERB_APP_SECRET={$appSecret}\n";
                $newLines .= "REVERB_HOST=127.0.0.1\n";
                $newLines .= "REVERB_PORT=8080\n";
                $newLines .= "REVERB_SCHEME=http\n";
                $newLines .= "REVERB_HOSTNAME=127.0.0.1\n";
                $newLines .= "\n# Vite Reverb Configuration\n";
                $newLines .= "VITE_REVERB_APP_KEY={$appKey}\n";
                $newLines .= "VITE_REVERB_HOST=127.0.0.1\n";
                $newLines .= "VITE_REVERB_PORT=8080\n";
                $newLines .= "VITE_REVERB_SCHEME=http\n";
                
                file_put_contents($envPath, $envContent . $newLines, FILE_APPEND);
                
                $this->info('Keys have been added to your .env file!');
            } else {
                $this->warn('Reverb keys already exist in .env file. Skipping automatic update.');
                $this->info('Please update manually if needed.');
            }
        } else {
            $this->warn('.env file not found. Please add the keys manually.');
        }

        return Command::SUCCESS;
    }
}
