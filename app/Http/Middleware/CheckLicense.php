<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\LicenseService;

class CheckLicense
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to license configuration routes
        if ($request->routeIs('license.*')) {
            return $next($request);
        }

        // Allow access to login/logout routes
        if ($request->routeIs('login') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Check if license is valid
        if (!$this->licenseService->isLicenseValid()) {
            // If user is not authenticated, redirect to login
            if (!auth()->check()) {
                return redirect()->route('login');
            }

            // If authenticated, redirect to license configuration
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Licença inválida ou não configurada',
                    'message' => 'Por favor, configure uma licença válida para continuar usando o sistema.',
                ], 403);
            }

            return redirect()->route('license.configure')
                ->with('error', 'Licença inválida ou não configurada. Por favor, configure uma licença válida.');
        }

        return $next($request);
    }
}
