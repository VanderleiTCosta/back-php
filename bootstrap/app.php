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
        // Não precisamos mais adicionar o HandleCors aqui,
        // pois o Laravel já o carrega por padrão e agora
        // ele lerá nosso novo arquivo config/cors.php
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();