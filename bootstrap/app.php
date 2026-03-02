<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'auto.login' => \App\Http\Middleware\AutoLoginMiddleware::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'trust.proxies' => \App\Http\Middleware\TrustProxies::class,
        ]);

        // TrustProxies must be global (prepended) to work for all requests
        $middleware->prepend(\App\Http\Middleware\TrustProxies::class);

        // Add SecurityHeaders to the web group
        $middleware->group('web', [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
