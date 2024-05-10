<?php

namespace App\Modules\TraceCollector\Framework\Http\Controllers;

use App\Modules\TraceCollector\Adapters\Service\ServiceAdapter;
use App\Modules\TraceCollector\Domain\Entities\Parameters\TraceUpdateParameters;
use App\Modules\TraceCollector\Domain\Entities\Parameters\TraceUpdateParametersList;
use App\Modules\TraceCollector\Domain\Entities\Parameters\TraceUpdateProfilingDataObject;
use App\Modules\TraceCollector\Domain\Entities\Parameters\TraceUpdateProfilingObject;
use App\Modules\TraceCollector\Domain\Entities\Parameters\TraceUpdateProfilingObjects;
use App\Modules\TraceCollector\Framework\Http\Requests\TraceUpdateRequest;
use App\Modules\TraceCollector\Framework\Http\Services\QueueDispatcher;
use SLoggerLaravel\SLoggerProcessor;
use Throwable;

readonly class TraceUpdateController
{
    public function __construct(
        private ServiceAdapter $serviceAdapter,
        private QueueDispatcher $queueDispatcher,
        private SLoggerProcessor $loggerProcessor
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(TraceUpdateRequest $request): void
    {
        $this->loggerProcessor->handleWithoutTracing(
            fn() => $this->handle($request)
        );
    }

    private function handle(TraceUpdateRequest $request): void
    {
        $validated = $request->validated();

        $serviceId = $this->serviceAdapter->getService()->id;

        $parametersList = new TraceUpdateParametersList();

        foreach ($validated['traces'] as $item) {
            $profiling = new TraceUpdateProfilingObjects();

            foreach ($item['profiling'] ?? [] as $profilingItem) {
                $profilingData = $profilingItem['data'];

                $profiling->add(
                    new TraceUpdateProfilingObject(
                        raw: $profilingItem['raw'],
                        calling: $profilingItem['calling'],
                        callable: $profilingItem['callable'],
                        data: new TraceUpdateProfilingDataObject(
                            numberOfCalls: $profilingData['number_of_calls'],
                            waitTimeInUs: $profilingData['wait_time_in_us'],
                            cpuTime: $profilingData['cpu_time'],
                            memoryUsageInBytes: $profilingData['memory_usage_in_bytes'],
                            peakMemoryUsageInBytes: $profilingData['peak_memory_usage_in_bytes'],
                        )
                    )
                );
            }

            $parameters = new TraceUpdateParameters(
                serviceId: $serviceId,
                traceId: $item['trace_id'],
                status: $item['status'],
                profiling: $profiling,
                tags: $item['tags'] ?? null,
                data: $item['data'] ?? null,
                duration: $item['duration'],
                memory: $item['memory'] ?? null,
                cpu: $item['cpu'] ?? null
            );

            $parametersList->add($parameters);
        }

        $this->queueDispatcher->updateMany($parametersList);
    }
}
