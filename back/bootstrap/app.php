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
        // Ajouter le middleware CORS global
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);
        
        // Ajouter le middleware dans le groupe API
        $middleware->api(append: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
        
        // Vous pouvez aussi l'ajouter comme alias pour l'utiliser dans les routes
        $middleware->alias([
            'cors' => \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();