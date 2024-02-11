<?php

namespace App\Modules\TracesAggregator\Dto;

use App\Models\Traces\Trace;
use App\Modules\TracesAggregator\Dto\Objects\TraceDataNodeObject;
use App\Modules\TracesAggregator\Services\TraceDataConverter;
use Carbon\Carbon;

readonly class TraceObject
{
    public function __construct(
        public int $serviceId,
        public string $traceId,
        public ?string $parentTraceId,
        public string $type,
        public array $tags,
        public TraceDataNodeObject $data,
        public Carbon $loggedAt,
        public Carbon $createdAt,
        public Carbon $updatedAt
    ) {
    }

    public static function fromModel(Trace $trace): static
    {
        return new static(
            serviceId: $trace->serviceId,
            traceId: $trace->traceId,
            parentTraceId: $trace->parentTraceId,
            type: $trace->type,
            tags: $trace->tags,
            data: (new TraceDataConverter($trace->data))->convert(),
            loggedAt: $trace->loggedAt,
            createdAt: $trace->createdAt,
            updatedAt: $trace->updatedAt
        );
    }
}
