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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['locale', 'sidebar_state']);

        $middleware->prependToPriorityList(
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Auth\AuthSetupMiddleware::class,
        );

        $middleware->alias([
            'banner.include' => \App\Http\Middleware\IncludeBanner::class,
            'auth.include'   => \App\Http\Middleware\Auth\AuthIncludeMiddleware::class,
            'auth.not_ready' => \App\Http\Middleware\Auth\AuthNotReadyMiddleware::class,
            'auth.owner'     => \App\Http\Middleware\Auth\AuthOwnerMiddleware::class,
            'auth.setup'     => \App\Http\Middleware\Auth\AuthSetupMiddleware::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\HandleLocale::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
