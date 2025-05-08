<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Solo permite al usuario con ID 3
        if (auth()->check() && auth()->user()->id === 3) {
            return $next($request);
        }

        abort(403, 'Acceso denegado. Solo el administrador puede acceder.');
    }
}
