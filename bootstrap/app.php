<?php

use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            require __DIR__.'/../routes/auth.php';
            require __DIR__.'/../routes/admin.php';
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['isAdmin' => \App\Http\Middleware\IsAdmin::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

$app->singleton(
    HttpKernel::class,
    \App\Http\Kernel::class
);

return $app;
