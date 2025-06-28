<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('barang:notify')->everyMinute()
            ->withoutOverlapping()
            ->evenInMaintenanceMode()
            ->onSuccess(function () {
                \Log::info('✅ barang:notify berhasil dijalankan pada ' . now());
            })
            ->onFailure(function () {
                \Log::error('❌ barang:notify gagal dijalankan pada ' . now());
            });

        $schedule->command('statusDonasi:notify')->everyMinute()
            ->withoutOverlapping()
            ->evenInMaintenanceMode()
            ->onSuccess(function () {
                \Log::info('✅ statusDonasi:notify berhasil dijalankan pada ' . now());
            })
            ->onFailure(function () {
                \Log::error('❌ statusDonasi:notify gagal dijalankan pada ' . now());
            });

        $schedule->command('topSeller:notify')->everyMinute()
            ->withoutOverlapping()
            ->evenInMaintenanceMode()
            ->onSuccess(function () {
                \Log::info('✅ topSeller:notify berhasil dijalankan pada ' . now());
            })
            ->onFailure(function () {
                \Log::error('❌ topSeller:notify gagal dijalankan pada ' . now());
            });
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['logged_in' => \App\Http\Middleware\CustomAuthMiddleware::class]);
        $middleware->append([
            // \App\Http\Middleware\VerifyCsrfToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
