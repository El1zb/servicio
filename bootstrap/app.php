<?php

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Render termina el HTTPS en su proxy y reenvía la petición como HTTP
        // plano + X-Forwarded-Proto — sin esto Laravel no confía en ese header
        // y genera URLs de assets (asset()/Vite) en http://, que Render
        // rechaza. El proxy de Render es el único que llega a este contenedor.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'no-back-cache' => \App\Http\Middleware\PreventBackHistoryCache::class,
        ]);

        RedirectIfAuthenticated::redirectUsing(fn ($request) => $request->user()->hasRole('admin')
            ? route('dashboard')
            : route('student-documents.index'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            $user = $request->user();

            return redirect($user && $user->hasRole('admin')
                ? route('dashboard')
                : route('student-documents.index'));
        });
    })->create();
