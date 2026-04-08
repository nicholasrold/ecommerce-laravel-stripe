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
        // 1. CSRF Exemption for Stripe Webhooks
        // Stripe cannot send a CSRF token, so we exclude this route.
        $middleware->validateCsrfTokens(except: [
            'webhook/stripe', 
        ]);

        // 2. Default Guest Redirection
        // Automatically redirect unauthenticated users to the admin login route.
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();