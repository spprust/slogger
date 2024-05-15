<?php

namespace App\Modules\TraceAggregator\Domain\Entities\Transports;

use App\Modules\TraceAggregator\Domain\Entities\Objects\ProfilingItemDataObject;
use App\Modules\TraceAggregator\Domain\Entities\Objects\ProfilingItemObject;
use App\Modules\TraceAggregator\Domain\Entities\Objects\ProfilingObject;
use Illuminate\Support\Str;

class TraceProfilingTransport
{
    public static function toObject(array $profiling): ProfilingObject
    {
        $objects = [];

        foreach ($profiling['items'] as $item) {
            $objects[] = new ProfilingItemObject(
                id: Str::uuid()->toString(),
                calling: $item['calling'],
                callable: $item['callable'],
                data: array_map(
                    fn(array $itemData) => new ProfilingItemDataObject(
                        name: $itemData['name'],
                        value: $itemData['value']
                    ),
                    $item['data']
                ),
            );
        }

        return new ProfilingObject(
            mainCaller: $profiling['mainCaller'],
            items: $objects
        );
    }
}