<?php

namespace App\Modules\Trace\Infrastructure;

use App\Modules\Common\Infrastructure\BaseServiceProvider;
use App\Modules\Trace\Contracts\Actions\MakeMetricIndicatorsActionInterface;
use App\Modules\Trace\Contracts\Actions\MakeTraceTimestampPeriodsActionInterface;
use App\Modules\Trace\Contracts\Actions\MakeTraceTimestampsActionInterface;
use App\Modules\Trace\Contracts\Actions\Mutations\ClearTracesActionInterface;
use App\Modules\Trace\Contracts\Actions\Mutations\CreateTraceAdminStoreActionInterface;
use App\Modules\Trace\Contracts\Actions\Mutations\CreateTraceManyActionInterface;
use App\Modules\Trace\Contracts\Actions\Mutations\DeleteTraceAdminStoreActionInterface;
use App\Modules\Trace\Contracts\Actions\Mutations\DeleteTraceDynamicIndexActionInterface;
use App\Modules\Trace\Contracts\Actions\Mutations\DeleteTracesActionInterface;
use App\Modules\Trace\Contracts\Actions\Mutations\FlushDynamicIndexesActionInterface;
use App\Modules\Trace\Contracts\Actions\Mutations\FreshTraceTimestampsActionInterface;
use App\Modules\Trace\Contracts\Actions\Mutations\StartMonitorTraceDynamicIndexesActionInterface;
use App\Modules\Trace\Contracts\Actions\Mutations\StopMonitorTraceDynamicIndexesActionInterface;
use App\Modules\Trace\Contracts\Actions\Mutations\UpdateTraceManyActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindMinLoggedAtTracesActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindStatusesActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTagsActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTraceAdminStoreActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTraceDetailActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTraceDynamicIndexesActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTraceDynamicIndexStatsActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTraceIdsActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTraceProfilingActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTracesActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTraceTimestampsActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTraceTreeActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTypesActionInterface;
use App\Modules\Trace\Contracts\Repositories\TraceAdminStoreRepositoryInterface;
use App\Modules\Trace\Contracts\Repositories\TraceContentRepositoryInterface;
use App\Modules\Trace\Contracts\Repositories\TraceDynamicIndexRepositoryInterface;
use App\Modules\Trace\Contracts\Repositories\TraceRepositoryInterface;
use App\Modules\Trace\Contracts\Repositories\TraceTimestampsRepositoryInterface;
use App\Modules\Trace\Contracts\Repositories\TraceTreeRepositoryInterface;
use App\Modules\Trace\Domain\Actions\MakeMetricIndicatorsAction;
use App\Modules\Trace\Domain\Actions\MakeTraceTimestampPeriodsAction;
use App\Modules\Trace\Domain\Actions\MakeTraceTimestampsAction;
use App\Modules\Trace\Domain\Actions\Mutations\ClearTracesAction;
use App\Modules\Trace\Domain\Actions\Mutations\CreateTraceAdminStoreAction;
use App\Modules\Trace\Domain\Actions\Mutations\CreateTraceManyAction;
use App\Modules\Trace\Domain\Actions\Mutations\DeleteTraceAdminStoreAction;
use App\Modules\Trace\Domain\Actions\Mutations\DeleteTraceDynamicIndexAction;
use App\Modules\Trace\Domain\Actions\Mutations\DeleteTracesAction;
use App\Modules\Trace\Domain\Actions\Mutations\FlushDynamicIndexesAction;
use App\Modules\Trace\Domain\Actions\Mutations\FreshTraceTimestampsAction;
use App\Modules\Trace\Domain\Actions\Mutations\StartMonitorTraceDynamicIndexesAction;
use App\Modules\Trace\Domain\Actions\Mutations\StopMonitorTraceDynamicIndexesAction;
use App\Modules\Trace\Domain\Actions\Mutations\UpdateTraceManyAction;
use App\Modules\Trace\Domain\Actions\Queries\FindMinLoggedAtTracesAction;
use App\Modules\Trace\Domain\Actions\Queries\FindStatusesAction;
use App\Modules\Trace\Domain\Actions\Queries\FindTagsAction;
use App\Modules\Trace\Domain\Actions\Queries\FindTraceAdminStoreAction;
use App\Modules\Trace\Domain\Actions\Queries\FindTraceDetailAction;
use App\Modules\Trace\Domain\Actions\Queries\FindTraceDynamicIndexesAction;
use App\Modules\Trace\Domain\Actions\Queries\FindTraceDynamicIndexStatsAction;
use App\Modules\Trace\Domain\Actions\Queries\FindTraceIdsAction;
use App\Modules\Trace\Domain\Actions\Queries\FindTraceProfilingAction;
use App\Modules\Trace\Domain\Actions\Queries\FindTracesAction;
use App\Modules\Trace\Domain\Actions\Queries\FindTraceTimestampsAction;
use App\Modules\Trace\Domain\Actions\Queries\FindTraceTreeAction;
use App\Modules\Trace\Domain\Actions\Queries\FindTypesAction;
use App\Modules\Trace\Domain\Services\TraceDynamicIndexInitializer;
use App\Modules\Trace\Domain\Services\TraceFieldTitlesService;
use App\Modules\Trace\Infrastructure\Commands\FlushDynamicIndexesCommand;
use App\Modules\Trace\Infrastructure\Commands\FreshTraceTimestampsCommand;
use App\Modules\Trace\Infrastructure\Commands\StartMonitorTraceDynamicIndexesCommand;
use App\Modules\Trace\Infrastructure\Commands\StopMonitorTraceDynamicIndexesCommand;
use App\Modules\Trace\Infrastructure\Http\Services\TraceDynamicIndexingActionService;
use App\Modules\Trace\Repositories\Services\TraceQueryBuilder;
use App\Modules\Trace\Repositories\TraceAdminStoreRepository;
use App\Modules\Trace\Repositories\TraceContentRepository;
use App\Modules\Trace\Repositories\TraceDynamicIndexRepository;
use App\Modules\Trace\Repositories\TraceRepository;
use App\Modules\Trace\Repositories\TraceTimestampsRepository;
use App\Modules\Trace\Repositories\TraceTreeRepository;

class TraceServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(TraceFieldTitlesService::class);
        $this->app->singleton(TraceQueryBuilder::class);
        $this->app->singleton(TraceDynamicIndexInitializer::class);
        $this->app->singleton(TraceDynamicIndexingActionService::class);

        parent::boot();

        $this->commands([
            FreshTraceTimestampsCommand::class,
            StartMonitorTraceDynamicIndexesCommand::class,
            StopMonitorTraceDynamicIndexesCommand::class,
            FlushDynamicIndexesCommand::class,
        ]);
    }

    protected function getContracts(): array
    {
        return [
            // repositories
            TraceRepositoryInterface::class                       => TraceRepository::class,
            TraceContentRepositoryInterface::class                => TraceContentRepository::class,
            TraceTreeRepositoryInterface::class                   => TraceTreeRepository::class,
            TraceTimestampsRepositoryInterface::class             => TraceTimestampsRepository::class,
            TraceDynamicIndexRepositoryInterface::class           => TraceDynamicIndexRepository::class,
            TraceAdminStoreRepositoryInterface::class             => TraceAdminStoreRepository::class,
            // actions
            MakeMetricIndicatorsActionInterface::class            => MakeMetricIndicatorsAction::class,
            MakeTraceTimestampPeriodsActionInterface::class       => MakeTraceTimestampPeriodsAction::class,
            MakeTraceTimestampsActionInterface::class             => MakeTraceTimestampsAction::class,
            // actions.mutations
            CreateTraceManyActionInterface::class                 => CreateTraceManyAction::class,
            ClearTracesActionInterface::class                     => ClearTracesAction::class,
            DeleteTracesActionInterface::class                    => DeleteTracesAction::class,
            FreshTraceTimestampsActionInterface::class            => FreshTraceTimestampsAction::class,
            UpdateTraceManyActionInterface::class                 => UpdateTraceManyAction::class,
            StartMonitorTraceDynamicIndexesActionInterface::class => StartMonitorTraceDynamicIndexesAction::class,
            StopMonitorTraceDynamicIndexesActionInterface::class  => StopMonitorTraceDynamicIndexesAction::class,
            FlushDynamicIndexesActionInterface::class             => FlushDynamicIndexesAction::class,
            DeleteTraceDynamicIndexActionInterface::class         => DeleteTraceDynamicIndexAction::class,
            CreateTraceAdminStoreActionInterface::class           => CreateTraceAdminStoreAction::class,
            DeleteTraceAdminStoreActionInterface::class           => DeleteTraceAdminStoreAction::class,
            // actions.queries
            FindStatusesActionInterface::class                    => FindStatusesAction::class,
            FindTagsActionInterface::class                        => FindTagsAction::class,
            FindTraceDetailActionInterface::class                 => FindTraceDetailAction::class,
            FindTraceProfilingActionInterface::class              => FindTraceProfilingAction::class,
            FindTracesActionInterface::class                      => FindTracesAction::class,
            FindTraceTimestampsActionInterface::class             => FindTraceTimestampsAction::class,
            FindTraceTreeActionInterface::class                   => FindTraceTreeAction::class,
            FindTypesActionInterface::class                       => FindTypesAction::class,
            FindTraceDynamicIndexesActionInterface::class         => FindTraceDynamicIndexesAction::class,
            FindTraceDynamicIndexStatsActionInterface::class      => FindTraceDynamicIndexStatsAction::class,
            FindMinLoggedAtTracesActionInterface::class           => FindMinLoggedAtTracesAction::class,
            FindTraceAdminStoreActionInterface::class             => FindTraceAdminStoreAction::class,
            FindTraceIdsActionInterface::class                    => FindTraceIdsAction::class,
        ];
    }
}
