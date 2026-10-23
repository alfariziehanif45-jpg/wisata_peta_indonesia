<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);

        // Tujuan jika user yang SUDAH login membuka halaman /login atau /register
        $middleware->redirectUsersTo(function (Request $request) {
            return $request->user()?->isAdmin()
                ? route('admin.dashboard')
                : route('map.index');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();