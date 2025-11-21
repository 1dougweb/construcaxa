<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\Setting;
use App\Services\LicenseService;
use App\Http\Middleware\CheckLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LicenseController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Show license configuration page
     */
    public function configure()
    {
        $license = License::current();
        
        return view('license.configure', [
            'license' => $license,
            'isValid' => $license ? $this->licenseService->isLicenseValid() : false,
        ]);
    }

    /**
     * Store license configuration
     */
    public function store(Request $request)
    {
        $request->validate([
            'license_token' => 'required|string|min:10',
        ]);

        // URL e API key vêm do código (.env ou diretamente no LicenseService)
        // Não são mais configuradas via frontend por segurança
        $result = $this->licenseService->store(
            $request->license_token,
            null // URL vem do código
        );

        if ($result) {
            return redirect()->route('license.configure')
                ->with('success', 'Licença configurada e validada com sucesso!');
        }

        $license = License::current();
        $error = $license->validation_error ?? 'Erro ao validar licença. Verifique o token e as configurações do servidor.';

        return redirect()->back()
            ->withInput()
            ->withErrors(['license_token' => $error]);
    }

    /**
     * Validate license manually
     */
    public function validateLicense(Request $request)
    {
        $result = $this->licenseService->validate();

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        if ($result['valid']) {
            return redirect()->route('license.configure')
                ->with('success', 'Licença validada com sucesso!');
        }

        return redirect()->back()
            ->with('error', $result['message'] ?? 'Erro ao validar licença.');
    }

    /**
     * Get license status for real-time validation
     */
    public function status(Request $request)
    {
        $status = $this->licenseService->getStatus();
        
        // Force validation if requested
        if ($request->has('validate')) {
            $validation = $this->licenseService->validate();
            $status = array_merge($status, $validation);
        }
        
        return response()->json($status);
    }
}
