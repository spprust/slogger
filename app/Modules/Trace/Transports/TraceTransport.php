<?php

namespace App\Modules\Trace\Transports;

use App\Modules\Trace\Entities\Trace\TraceObject;
use App\Modules\Trace\Entities\Trace\TraceServiceObject;
use App\Modules\Trace\Repositories\Dto\TraceDto;

class TraceTransport
{
    public static function toObject(TraceDto $dto): TraceObject
    {
        return new TraceObject(
            service: $dto->service
                ? new TraceServiceObject(
                    id: $dto->service->id,
                    name: $dto->service->name,
                )
                : null,
            traceId: $dto->traceId,
            parentTraceId: $dto->parentTraceId,
            type: $dto->type,
            status: $dto->status,
            tags: $dto->tags,
            duration: $dto->duration,
            memory: $dto->memory,
            cpu: $dto->cpu,
            loggedAt: $dto->loggedAt,
            createdAt: $dto->createdAt,
            updatedAt: $dto->updatedAt
        );
    }
}
