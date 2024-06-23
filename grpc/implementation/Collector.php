<?php

namespace GRPCServices;

use App\Modules\Service\Domain\Actions\Interfaces\FindServiceByTokenActionInterface;
use App\Modules\Trace\Domain\Actions\Interfaces\MakeTraceTimestampsActionInterface;
use App\Modules\Trace\Domain\Entities\Parameters\Profilling\TraceUpdateProfilingDataObject;
use App\Modules\Trace\Domain\Entities\Parameters\Profilling\TraceUpdateProfilingObject;
use App\Modules\Trace\Domain\Entities\Parameters\Profilling\TraceUpdateProfilingObjects;
use App\Modules\Trace\Domain\Entities\Parameters\TraceCreateParameters;
use App\Modules\Trace\Domain\Entities\Parameters\TraceCreateParametersList;
use App\Modules\Trace\Domain\Entities\Parameters\TraceUpdateParameters;
use App\Modules\Trace\Domain\Entities\Parameters\TraceUpdateParametersList;
use App\Modules\Trace\Framework\Http\Services\QueueDispatcher;
use GRPC\Collector\TraceCollectorInterface;
use GRPC\Collector\TraceCollectorResponse;
use GRPC\Collector\TraceCreateObject;
use GRPC\Collector\TraceCreateRequest;
use GRPC\Collector\TraceProfilingItemDataItemObject;
use GRPC\Collector\TraceProfilingItemObject;
use GRPC\Collector\TraceProfilingItemsObject;
use GRPC\Collector\TraceUpdateObject;
use GRPC\Collector\TraceUpdateRequest;
use Illuminate\Support\Carbon;
use Spiral\RoadRunner\GRPC;
use Throwable;

readonly class Collector implements TraceCollectorInterface
{
    public function __construct(
        private FindServiceByTokenActionInterface $findServiceByTokenAction,
        private MakeTraceTimestampsActionInterface $makeTraceTimestampsAction,
        private QueueDispatcher $tracesServiceQueueDispatcher,
    ) {
    }

    public function Create(GRPC\ContextInterface $ctx, TraceCreateRequest $in): TraceCollectorResponse
    {
        $serviceId = $this->detectServiceIdByCtx($ctx);

        if (!$serviceId) {
            return new TraceCollectorResponse([
                'status_code' => 401,
                'message'     => 'Service not found',
            ]);
        }

        try {
            $this->onCreate(
                serviceId: $serviceId,
                in: $in
            );
        } catch (Throwable $exception) {
            return new TraceCollectorResponse([
                'status_code' => 500,
                'message'     => $exception->getMessage(),
            ]);
        }

        return new TraceCollectorResponse([
            'status_code' => 200,
            'message'     => 'Ok',
        ]);
    }

    public function Update(GRPC\ContextInterface $ctx, TraceUpdateRequest $in): TraceCollectorResponse
    {
        $serviceId = $this->detectServiceIdByCtx($ctx);

        if (!$serviceId) {
            return new TraceCollectorResponse([
                'status_code' => 401,
                'message'     => 'Service not found',
            ]);
        }

        try {
            $this->onUpdate(
                serviceId: $serviceId,
                in: $in
            );
        } catch (Throwable $exception) {
            return new TraceCollectorResponse([
                'status_code' => 500,
                'message'     => $exception->getMessage(),
            ]);
        }

        return new TraceCollectorResponse([
            'status_code' => 200,
            'message'     => 'Ok',
        ]);
    }

    private function detectServiceIdByCtx(GRPC\ContextInterface $ctx): ?int
    {
        $headers = $ctx->getValue('authorization');

        if (!is_array($headers)) {
            return null;
        }

        $header = $headers[0] ?? null;

        if (!$header) {
            return null;
        }

        $position = strrpos($header, 'Bearer ');

        if ($position === false) {
            return null;
        }

        $slicedHeader = substr($header, $position + 7);

        $bearer = str_contains($slicedHeader, ',') ? strstr($slicedHeader, ',', true) : $slicedHeader;

        return $this->findServiceByTokenAction->handle($bearer)?->id;
    }

    private function onCreate(int $serviceId, TraceCreateRequest $in): void
    {
        $parameters = new TraceCreateParametersList();

        $traces = $in->getTraces();

        for ($index = 0; $index < $traces->count(); $index++) {
            /** @var TraceCreateObject $trace */
            $trace = $traces[$index];

            $loggedAt = new Carbon($trace->getLoggedAt()->toDateTime());

            $traceParameters = new TraceCreateParameters(
                serviceId: $serviceId,
                traceId: $trace->getTraceId(),
                parentTraceId: $trace->getParentTraceId()->getValue(),
                type: $trace->getType(),
                status: $trace->getStatus(),
                tags: collect($trace->getTags())->toArray(),
                data: $trace->getData(),
                duration: $trace->getDuration()?->getValue(),
                memory: $trace->getMemory()?->getValue(),
                cpu: $trace->getCpu()?->getValue(),
                timestamps: $this->makeTraceTimestampsAction->handle(
                    date: $loggedAt
                ),
                loggedAt: $loggedAt,
            );

            $parameters->add($traceParameters);
        }

        $this->tracesServiceQueueDispatcher->createMany($parameters);
    }

    private function onUpdate(int $serviceId, TraceUpdateRequest $in): void
    {
        $parameters = new TraceUpdateParametersList();

        foreach ($in->getTraces() as $trace) {
            /** @var TraceUpdateObject $trace */

            $traceParameters = new TraceUpdateParameters(
                serviceId: $serviceId,
                traceId: $trace->getTraceId(),
                status: $trace->getStatus(),
                profiling: $this->makeProfiling(
                    $trace->getProfiling()
                ),
                tags: $trace->getTags(),
                data: json_encode($trace->getData(), true),
                duration: $trace->getDuration(),
                memory: $trace->getMemory(),
                cpu: $trace->getCpu(),
            );

            $parameters->add($traceParameters);
        }

        $this->tracesServiceQueueDispatcher->updateMany($parameters);
    }

    private function makeProfiling(?TraceProfilingItemsObject $object): ?TraceUpdateProfilingObjects
    {
        if (!$object?->getMainCaller()) {
            return null;
        }

        $result = new TraceUpdateProfilingObjects(
            mainCaller: $object->getMainCaller()
        );

        foreach ($object->getItems() as $item) {
            /** @var TraceProfilingItemObject $item */

            $result->add(
                new TraceUpdateProfilingObject(
                    raw: $item->getRaw(),
                    calling: $item->getCalling(),
                    callable: $item->getCallable(),
                    data: array_map(
                        function (TraceProfilingItemDataItemObject $dataItem) {
                            $value = $dataItem->getValue();

                            return new TraceUpdateProfilingDataObject(
                                name: $dataItem->getName(),
                                value: $value->getInt() ?? $value->getDouble()
                            );
                        },
                        collect($item->getData())->toArray()
                    ),
                )
            );
        }

        return $result;
    }
}
