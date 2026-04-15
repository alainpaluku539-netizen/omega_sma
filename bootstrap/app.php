<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ForceUtf8; // N'oubliez pas l'import

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Enregistrement de ton middleware de clé API
        $middleware->alias([
            'api_key' => \App\Http\Middleware\ApiKeyMiddleware::class,
        ]);

        // Optionnel : Exclure les routes IoT de la vérification CSRF 
        // (Utile si ton script Python envoie des POST sans jeton)
        $middleware->validateCsrfTokens(except: [
            'api/sensors/*',
            'api/controls/*',
            'api/trigger-sync'
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
