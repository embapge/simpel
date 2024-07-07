<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // Route::middleware(["throttle:api"])->prefix("api/v1")->name("api.v1.")->group(base_path("routes/api/v1.php"));
            Route::middleware([])->prefix("api/v1")->name("api.v1.")->group(base_path("routes/api/v1.php"));
            Route::middleware(["throttle:notification"])->prefix("notification/v1")->name("notification.v1.")->group(base_path("routes/notification/v1.php"));
        }
    )->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
        ['prefix' => 'api', 'middleware' => ['api', 'auth:sanctum']],
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
