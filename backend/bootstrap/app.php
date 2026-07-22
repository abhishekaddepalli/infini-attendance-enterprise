<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'tenant' => \Infini\Attendance\Middleware\IdentifyTenant::class,
            'rbac' => \Infini\Attendance\Middleware\CheckPermission::class,
            '2fa' => \Infini\Attendance\Middleware\TwoFactorAuthenticate::class,
            'subscription' => \Infini\Attendance\Middleware\CheckSubscription::class,
            'feature' => \Infini\Attendance\Middleware\CheckFeature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
