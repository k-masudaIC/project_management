<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withProviders([                                    // ← 追加
        App\Providers\AuthServiceProvider::class,        // ← 追加
    ])
    ->withSchedule(function (Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('tasks:send-deadline-alerts')->hourly();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            '*', // テスト環境では全除外、または本番では必要なパスのみ
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
