<?php

use App\Modules\Auth\Framework\Http\Controllers\LoginController;
use App\Modules\Auth\Framework\Http\Controllers\MeController;
use App\Modules\Auth\Framework\Http\Middlewares\AuthMiddleware;
use App\Modules\Dashboard\Framework\Http\Controllers\DatabaseStatController;
use App\Modules\Dashboard\Framework\Http\Controllers\ServiceStatController;
use App\Modules\Service\Framework\Http\Controllers\ServiceController;
use App\Modules\TraceAggregator\Framework\Http\Controllers\TraceContentController;
use App\Modules\TraceAggregator\Framework\Http\Controllers\TraceController;
use App\Modules\TraceAggregator\Framework\Http\Controllers\TraceProfilingController;
use App\Modules\TraceAggregator\Framework\Http\Controllers\TraceTreeController;
use App\Modules\TraceCleaner\Framework\Http\Controllers\ProcessController;
use App\Modules\TraceCleaner\Framework\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')
    ->as('auth.')
    ->group(function () {
        Route::get('/me', MeController::class)->name('me');
        Route::post('/login', LoginController::class)->withoutMiddleware(AuthMiddleware::class)->name('login');
    });

Route::prefix('/dashboard')
    ->as('dashboard.')
    ->group(function () {
        Route::get('/database', [DatabaseStatController::class, 'index'])->name('index');
        Route::get('/service-stat', [ServiceStatController::class, 'index'])->name('index');
    });

Route::prefix('/services')
    ->as('services.')
    ->group(function () {
        Route::get('', [ServiceController::class, 'index'])->name('index');
    });

Route::prefix('/trace-aggregator')
    ->as('trace-aggregator.')
    ->group(function () {
        Route::prefix('/traces')
            ->as('traces.')
            ->group(function () {
                Route::post('', [TraceController::class, 'index'])->name('index');

                Route::prefix('{traceId}')
                    ->group(function () {
                        Route::get('', [TraceController::class, 'show'])->name('show');
                        Route::get('/tree', [TraceTreeController::class, 'index'])->name('tree');
                        Route::get('/profiling', [TraceProfilingController::class, 'index'])->name('profiling');
                    });
            });

        Route::prefix('/traces-content')
            ->as('traces-content.')
            ->group(function () {
                Route::post('/types', [TraceContentController::class, 'types'])->name('types');
                Route::post('/tags', [TraceContentController::class, 'tags'])->name('tags');
                Route::post('/statuses', [TraceContentController::class, 'statuses'])->name('statuses');
            });
    });

Route::prefix('/trace-cleaner')
    ->as('trace-cleaner.')
    ->group(function () {
        Route::prefix('/settings')
            ->as('settings.')
            ->group(function () {
                Route::get('/', [SettingController::class, 'index'])
                    ->name('index');
                Route::post('/', [SettingController::class, 'store'])
                    ->name('store');
                Route::patch('/{settingId}', [SettingController::class, 'update'])
                    ->name('update');
                Route::delete('/{settingId}', [SettingController::class, 'destroy'])
                    ->name('destroy');
                Route::get('/{settingId}/processes', [ProcessController::class, 'index'])
                    ->name('processes');
            });
    });