<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function settings()
    {
        $settings = [
            'google_maps_api_key' => Setting::get('google_maps_api_key', ''),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'google_maps_api_key' => 'nullable|string|max:255',
        ]);

        Setting::set(
            'google_maps_api_key', 
            $request->google_maps_api_key, 
            'string', 
            'Chave da API do Google Maps para exibição de mapas'
        );

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }

    public function emailSettings()
    {
        $settings = [
            'mail_mailer' => Setting::get('mail_mailer', 'smtp'),
            'mail_host' => Setting::get('mail_host', ''),
            'mail_port' => Setting::get('mail_port', '587'),
            'mail_username' => Setting::get('mail_username', ''),
            'mail_password' => Setting::get('mail_password', ''),
            'mail_encryption' => Setting::get('mail_encryption', 'tls'),
            'mail_from_address' => Setting::get('mail_from_address', ''),
            'mail_from_name' => Setting::get('mail_from_name', ''),
        ];

        return view('admin.email-settings', compact('settings'));
    }

    public function updateEmailSettings(Request $request)
    {
        $request->validate([
            'mail_mailer' => 'required|in:smtp,sendmail,log',
            'mail_host' => 'required_if:mail_mailer,smtp|nullable|string|max:255',
            'mail_port' => 'required_if:mail_mailer,smtp|nullable|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        Setting::set('mail_mailer', $request->mail_mailer, 'string', 'Tipo de mailer para envio de emails');
        Setting::set('mail_host', $request->mail_host ?? '', 'string', 'Host do servidor SMTP');
        Setting::set('mail_port', $request->mail_port ?? '587', 'string', 'Porta do servidor SMTP');
        Setting::set('mail_username', $request->mail_username ?? '', 'string', 'Usuário do servidor SMTP');
        
        // Só atualizar senha se foi fornecida (não vazia)
        if ($request->filled('mail_password')) {
            Setting::set('mail_password', $request->mail_password, 'string', 'Senha do servidor SMTP');
        }
        
        Setting::set('mail_encryption', $request->mail_encryption ?? 'tls', 'string', 'Tipo de criptografia (tls/ssl)');
        Setting::set('mail_from_address', $request->mail_from_address, 'string', 'Endereço de email remetente');
        Setting::set('mail_from_name', $request->mail_from_name, 'string', 'Nome do remetente');

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }

    public function sendTestEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('test_error', 'Por favor, forneça um email válido.');
        }

        try {
            // Aplicar configurações do banco dinamicamente
            self::applyEmailSettings();

            // Enviar email de teste
            Mail::raw('Este é um email de teste do sistema. Se você recebeu esta mensagem, a configuração SMTP está funcionando corretamente.', function ($message) use ($request) {
                $fromAddress = Setting::get('mail_from_address', config('mail.from.address'));
                $fromName = Setting::get('mail_from_name', config('mail.from.name'));
                
                $message->to($request->test_email)
                    ->subject('Email de Teste - Sistema')
                    ->from($fromAddress, $fromName);
            });

            return redirect()->back()->with('test_success', 'Email de teste enviado com sucesso para ' . $request->test_email . '!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('test_error', 'Erro ao enviar email de teste: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Aplica as configurações de email do banco de dados dinamicamente
     *
     * Tornado público/estático para ser reutilizado em outros pontos (ex: envio de credenciais).
     */
    public static function applyEmailSettings(): void
    {
        $mailer = Setting::get('mail_mailer', 'smtp');
        
        Config::set('mail.default', $mailer);
        Config::set('mail.from.address', Setting::get('mail_from_address', config('mail.from.address')));
        Config::set('mail.from.name', Setting::get('mail_from_name', config('mail.from.name')));

        if ($mailer === 'smtp') {
            Config::set('mail.mailers.smtp.host', Setting::get('mail_host', ''));
            Config::set('mail.mailers.smtp.port', Setting::get('mail_port', '587'));
            Config::set('mail.mailers.smtp.username', Setting::get('mail_username', ''));
            Config::set('mail.mailers.smtp.password', Setting::get('mail_password', ''));
            Config::set('mail.mailers.smtp.encryption', Setting::get('mail_encryption', 'tls'));
        }
    }
}
