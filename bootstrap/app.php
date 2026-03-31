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
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'isUser' => \App\Http\Middleware\IsUserMiddleware::class,
            'isContributor' => \App\Http\Middleware\IsContributorMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request): string {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            return route('login');
        });

        $middleware->redirectUsersTo(function (Request $request): string {
            return $request->user()?->isAdmin()
                ? route('admin.users')
                : route('dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
