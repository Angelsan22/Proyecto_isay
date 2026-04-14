<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Security\SessionGuard;

/**
 * Middleware para proteger rutas que requieren autenticación de cliente.
 */
class ClienteAutenticado
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!SessionGuard::check()) {
            return redirect()->route('cliente.login')
                ->with('error', 'Debes iniciar sesión para acceder a esta sección.');
        }

        return $next($request);
    }
}
