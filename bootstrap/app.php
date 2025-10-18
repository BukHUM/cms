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
        // Register all middleware aliases in one call
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'permission.any' => \App\Http\Middleware\CheckAnyPermission::class,
            'permission.all' => \App\Http\Middleware\CheckAllPermissions::class,
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'settings.update.access' => \App\Http\Middleware\SettingsUpdateAccess::class,
            'settings.backup.access' => \App\Http\Middleware\SettingsBackupAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
