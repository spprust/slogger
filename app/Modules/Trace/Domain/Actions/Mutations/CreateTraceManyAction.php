<?php

namespace App\Modules\Trace\Domain\Actions\Mutations;

use App\Modules\Trace\Contracts\Actions\Mutations\CreateTraceManyActionInterface;
use App\Modules\Trace\Contracts\Repositories\TraceRepositoryInterface;
use App\Modules\Trace\Entities\Trace\Timestamp\TraceTimestampMetricObject;
use App\Modules\Trace\Parameters\TraceCreateParameters;
use App\Modules\Trace\Parameters\TraceCreateParametersList;
use App\Modules\Trace\Repositories\Dto\Timestamp\TraceTimestampMetricDto;
use App\Modules\Trace\Repositories\Dto\TraceCreateDto;

readonly class CreateTraceManyAction implements CreateTraceManyActionInterface
{
    public function __construct(
        private TraceRepositoryInterface $traceRepository
    ) {
    }

    public function handle(TraceCreateParametersList $parametersList): void
    {
        $this->traceRepository->createMany(
            array_map(
                fn(TraceCreateParameters $parameters) => new TraceCreateDto(
                    serviceId: $parameters->serviceId,
                    traceId: $parameters->traceId,
                    parentTraceId: $parameters->parentTraceId,
                    type: $parameters->type,
                    status: $parameters->status,
                    tags: $parameters->tags,
                    data: $parameters->data,
                    duration: $parameters->duration,
                    memory: $parameters->memory,
                    cpu: $parameters->cpu,
                    timestamps: array_map(
                        fn(TraceTimestampMetricObject $metric) => new TraceTimestampMetricDto(
                            key: $metric->key,
                            value: $metric->value,
                        ),
                        $parameters->timestamps
                    ),
                    loggedAt: $parameters->loggedAt,
                ),
                $parametersList->getItems()
            )
        );
    }
}
