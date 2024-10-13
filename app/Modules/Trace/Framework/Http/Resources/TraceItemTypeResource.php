<?php

namespace App\Modules\Trace\Framework\Http\Resources;

use App\Modules\Common\Infrastructure\Http\Resources\AbstractApiResource;
use App\Modules\Trace\Domain\Entities\Objects\TraceTypeCountedObject;

class TraceItemTypeResource extends AbstractApiResource
{
    private string $type;
    private int $count;

    public function __construct(TraceTypeCountedObject $type)
    {
        parent::__construct($type);

        $this->type = $type->type;
        $this->count = $type->count;
    }
}
