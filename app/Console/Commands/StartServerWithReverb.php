<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class StartServerWithReverb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serve:all 
                            {--host=127.0.0.1 : The host address to serve the application on}
                            {--port=8000 : The port to serve the application on}
                            {--reverb-port=8080 : The port for Reverb WebSocket server}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Laravel development server and Reverb WebSocket server together';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $host = $this->option('host');
        $port = $this->option('port');
        $reverbPort = $this->option('reverb-port');

        $this->info('🚀 Iniciando servidor Laravel e Reverb WebSocket...');
        $this->info("📡 Servidor Laravel: http://{$host}:{$port}");
        $this->info("🔌 Reverb WebSocket: ws://{$host}:{$reverbPort}");

        // Iniciar Reverb em background
        $reverbProcess = new Process([
            PHP_BINARY,
            base_path('artisan'),
            'reverb:start',
            '--host=' . $host,
            '--port=' . $reverbPort,
        ], base_path(), null, null, null);

        $reverbProcess->start(function ($type, $buffer) {
            if (Process::ERR === $type) {
                $this->error('Reverb ERR: ' . $buffer);
            } else {
                $this->line('Reverb: ' . $buffer);
            }
        });

        // Aguardar um pouco para Reverb iniciar
        sleep(2);

        // Verificar se Reverb está rodando
        if (!$reverbProcess->isRunning()) {
            $this->error('❌ Falha ao iniciar Reverb. Verifique se a porta está disponível.');
            return 1;
        }

        $this->info('✅ Reverb iniciado com sucesso!');
        $this->info('🌐 Iniciando servidor Laravel...');

        // Iniciar servidor Laravel (bloqueia até ser interrompido)
        $serverProcess = new Process([
            PHP_BINARY,
            base_path('artisan'),
            'serve',
            '--host=' . $host,
            '--port=' . $port,
        ], base_path(), null, null, null);

        // Handler para quando o servidor Laravel for interrompido
        $serverProcess->setTimeout(null);
        $serverProcess->start(function ($type, $buffer) {
            echo $buffer;
        });

        // Aguardar até que o processo seja interrompido
        while ($serverProcess->isRunning() && $reverbProcess->isRunning()) {
            usleep(100000); // 100ms
        }

        // Parar Reverb quando o servidor Laravel parar
        if ($reverbProcess->isRunning()) {
            $this->info('🛑 Parando Reverb...');
            $reverbProcess->stop();
        }

        return 0;
    }
}
