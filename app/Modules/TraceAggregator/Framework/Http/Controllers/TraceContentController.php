<?php

namespace App\Modules\TraceAggregator\Framework\Http\Controllers;

use App\Modules\TraceAggregator\Domain\Entities\Parameters\PeriodParameters;
use App\Modules\TraceAggregator\Domain\Entities\Parameters\TraceFindStatusesParameters;
use App\Modules\TraceAggregator\Domain\Entities\Parameters\TraceFindTagsParameters;
use App\Modules\TraceAggregator\Domain\Entities\Parameters\TraceFindTypesParameters;
use App\Modules\TraceAggregator\Framework\Http\Controllers\Traits\MakeDataFilterParameterTrait;
use App\Modules\TraceAggregator\Framework\Http\Requests\TraceFindStatusesRequest;
use App\Modules\TraceAggregator\Framework\Http\Requests\TraceFindTagsRequest;
use App\Modules\TraceAggregator\Framework\Http\Requests\TraceFindTypesRequest;
use App\Modules\TraceAggregator\Framework\Http\Responses\StringValueResponse;
use App\Modules\TraceAggregator\Repositories\Interfaces\TraceContentRepositoryInterface;
use Ifksco\OpenApiGenerator\Attributes\OaListItemTypeAttribute;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

readonly class TraceContentController
{
    use MakeDataFilterParameterTrait;

    public function __construct(
        private TraceContentRepositoryInterface $repository
    ) {
    }

    #[OaListItemTypeAttribute(StringValueResponse::class)]
    public function types(TraceFindTypesRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        return StringValueResponse::collection(
            $this->repository->findTypes(
                new TraceFindTypesParameters(
                    serviceIds: array_map('intval', $validated['service_ids'] ?? []),
                    text: $validated['text'] ?? null,
                    loggingPeriod: PeriodParameters::fromStringValues(
                        from: $validated['logging_from'] ?? null,
                        to: $validated['logging_to'] ?? null,
                    ),
                    data: $this->makeDataFilterParameter($validated),
                )
            )
        );
    }

    #[OaListItemTypeAttribute(StringValueResponse::class)]
    public function tags(TraceFindTagsRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        return StringValueResponse::collection(
            $this->repository->findTags(
                new TraceFindTagsParameters(
                    serviceIds: array_map('intval', $validated['service_ids'] ?? []),
                    text: $validated['text'] ?? null,
                    loggingPeriod: PeriodParameters::fromStringValues(
                        from: $validated['logging_from'] ?? null,
                        to: $validated['logging_to'] ?? null,
                    ),
                    types: $validated['types'] ?? [],
                    data: $this->makeDataFilterParameter($validated),
                )
            )
        );
    }

    #[OaListItemTypeAttribute(StringValueResponse::class)]
    public function statuses(TraceFindStatusesRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        return StringValueResponse::collection(
            $this->repository->findStatuses(
                new TraceFindStatusesParameters(
                    serviceIds: array_map('intval', $validated['service_ids'] ?? []),
                    text: $validated['text'] ?? null,
                    loggingPeriod: PeriodParameters::fromStringValues(
                        from: $validated['logging_from'] ?? null,
                        to: $validated['logging_to'] ?? null,
                    ),
                    types: $validated['types'] ?? [],
                    tags: $validated['tags'] ?? [],
                    data: $this->makeDataFilterParameter($validated),
                )
            )
        );
    }
}
