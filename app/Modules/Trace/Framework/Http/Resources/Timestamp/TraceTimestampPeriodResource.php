<?php

namespace App\Modules\Trace\Framework\Http\Resources\Timestamp;

use App\Modules\Common\Infrastructure\Http\Resources\AbstractApiResource;
use App\Modules\Trace\Domain\Entities\Objects\Timestamp\TraceTimestampPeriodObject;
use Ifksco\OpenApiGenerator\Attributes\OaListItemTypeAttribute;

class TraceTimestampPeriodResource extends AbstractApiResource
{
    private TraceTimestampPeriodValueResource $period;
    #[OaListItemTypeAttribute(TraceTimestampPeriodTimestampResource::class)]
    private array $timestamps;

    public function __construct(TraceTimestampPeriodObject $resource)
    {
        parent::__construct($resource);

        $this->period     = new TraceTimestampPeriodValueResource($resource->period);
        $this->timestamps = TraceTimestampPeriodTimestampResource::mapIntoMe($resource->timestamps);
    }
}
