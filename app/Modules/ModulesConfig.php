<?php

namespace App\Modules;

use App\Modules\Auth\Framework\AuthServiceProvider;
use App\Modules\Dashboard\DashboardProvider;
use App\Modules\Service\Framework\ServiceServiceProvider;
use App\Modules\TraceAggregator\TraceAggregatorProvider;
use App\Modules\TraceCleaner\Framework\TraceCleanerServiceProvider;
use App\Modules\TraceCollector\Framework\TraceCollectorServiceProvider;
use App\Modules\User\Framework\UserServiceProvider;

class ModulesConfig
{
    public static function getProviders(): array
    {
        return [
            ServiceServiceProvider::class,
            TraceCollectorServiceProvider::class,
            TraceAggregatorProvider::class,
            AuthServiceProvider::class,
            UserServiceProvider::class,
            DashboardProvider::class,
            TraceCleanerServiceProvider::class,
        ];
    }
}
