<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Evita que el navegador restaure /login (u otras páginas auth-sensibles)
 * desde el bfcache al usar el botón "atrás". Sin esto, tras iniciar sesión
 * y navegar, "atrás" muestra el /login viejo tal cual quedó (sin volver a
 * pasar por el middleware guest), en vez de redirigir a la ruta correcta.
 */
class PreventBackHistoryCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

        return $response;
    }
}
