<?php

namespace App\Modules\Trace\Infrastructure\Http\Controllers;

use App\Modules\Trace\Contracts\Actions\Queries\FindStatusesActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTagsActionInterface;
use App\Modules\Trace\Contracts\Actions\Queries\FindTypesActionInterface;
use App\Modules\Trace\Domain\Exceptions\TraceDynamicIndexNotInitException;
use App\Modules\Trace\Infrastructure\Http\Controllers\Traits\MakeDataFilterParameterTrait;
use App\Modules\Trace\Infrastructure\Http\Requests\TraceFindStatusesRequest;
use App\Modules\Trace\Infrastructure\Http\Requests\TraceFindTagsRequest;
use App\Modules\Trace\Infrastructure\Http\Requests\TraceFindTypesRequest;
use App\Modules\Trace\Infrastructure\Http\Resources\TraceStringFieldResource;
use App\Modules\Trace\Infrastructure\Http\Services\TraceDynamicIndexingActionService;
use App\Modules\Trace\Parameters\PeriodParameters;
use App\Modules\Trace\Parameters\TraceFindStatusesParameters;
use App\Modules\Trace\Parameters\TraceFindTagsParameters;
use App\Modules\Trace\Parameters\TraceFindTypesParameters;
use Ifksco\OpenApiGenerator\Attributes\OaListItemTypeAttribute;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

readonly class TraceContentController
{
    use MakeDataFilterParameterTrait;

    public function __construct(
        private FindTypesActionInterface $findTypesAction,
        private FindTagsActionInterface $findTagsAction,
        private FindStatusesActionInterface $findStatusesAction,
        private TraceDynamicIndexingActionService $traceDynamicIndexingActionService
    ) {
    }

    /**
     * @throws TraceDynamicIndexNotInitException
     */
    #[OaListItemTypeAttribute(TraceStringFieldResource::class)]
    public function types(TraceFindTypesRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        return TraceStringFieldResource::collection(
            $this->traceDynamicIndexingActionService->handle(
                fn() => $this->findTypesAction->handle(
                    new TraceFindTypesParameters(
                        serviceIds: array_map('intval', $validated['service_ids'] ?? []),
                        text: $validated['text'] ?? null,
                        loggingPeriod: PeriodParameters::fromStringValues(
                            from: $validated['logging_from'] ?? null,
                            to: $validated['logging_to'] ?? null,
                        ),
                        // TODO: long executing for float values
                        //durationFrom: $validated['duration_from'] ?? null,
                        //durationTo: $validated['duration_to'] ?? null,
                        //memoryFrom: $validated['memory_from'] ?? null,
                        //memoryTo: $validated['memory_to'] ?? null,
                        //cpuFrom: $validated['cpu_from'] ?? null,
                        //cpuTo: $validated['cpu_to'] ?? null,
                        //data: $this->makeDataFilterParameter($validated),
                        //hasProfiling: ($validated['has_profiling'] ?? null) ?: null,
                    )
                )
            )
        );
    }

    /**
     * @throws TraceDynamicIndexNotInitException
     */
    #[OaListItemTypeAttribute(TraceStringFieldResource::class)]
    public function tags(TraceFindTagsRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        return TraceStringFieldResource::collection(
            $this->traceDynamicIndexingActionService->handle(
                fn() => $this->findTagsAction->handle(
                    new TraceFindTagsParameters(
                        serviceIds: array_map('intval', $validated['service_ids'] ?? []),
                        text: $validated['text'] ?? null,
                        loggingPeriod: PeriodParameters::fromStringValues(
                            from: $validated['logging_from'] ?? null,
                            to: $validated['logging_to'] ?? null,
                        ),
                        types: $validated['types'] ?? [],
                        // TODO: long executing for float values
                        //durationFrom: $validated['duration_from'] ?? null,
                        //durationTo: $validated['duration_to'] ?? null,
                        //memoryFrom: $validated['memory_from'] ?? null,
                        //memoryTo: $validated['memory_to'] ?? null,
                        //cpuFrom: $validated['cpu_from'] ?? null,
                        //cpuTo: $validated['cpu_to'] ?? null,
                        //data: $this->makeDataFilterParameter($validated),
                        //hasProfiling: ($validated['has_profiling'] ?? null) ?: null,
                    )
                )
            )
        );
    }

    /**
     * @throws TraceDynamicIndexNotInitException
     */
    #[OaListItemTypeAttribute(TraceStringFieldResource::class)]
    public function statuses(TraceFindStatusesRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        return TraceStringFieldResource::collection(
            $this->traceDynamicIndexingActionService->handle(
                fn() => $this->findStatusesAction->handle(
                    new TraceFindStatusesParameters(
                        serviceIds: array_map('intval', $validated['service_ids'] ?? []),
                        text: $validated['text'] ?? null,
                        loggingPeriod: PeriodParameters::fromStringValues(
                            from: $validated['logging_from'] ?? null,
                            to: $validated['logging_to'] ?? null,
                        ),
                        types: $validated['types'] ?? [],
                        tags: $validated['tags'] ?? [],
                        // TODO: long executing for float values
                        //durationFrom: $validated['duration_from'] ?? null,
                        //durationTo: $validated['duration_to'] ?? null,
                        //memoryFrom: $validated['memory_from'] ?? null,
                        //memoryTo: $validated['memory_to'] ?? null,
                        //cpuFrom: $validated['cpu_from'] ?? null,
                        //cpuTo: $validated['cpu_to'] ?? null,
                        //data: $this->makeDataFilterParameter($validated),
                        //hasProfiling: ($validated['has_profiling'] ?? null) ?: null,
                    )
                )
            )
        );
    }
}
