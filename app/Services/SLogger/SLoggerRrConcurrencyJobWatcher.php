<?php

namespace App\Services\SLogger;

use Illuminate\Support\Carbon;
use RrConcurrency\Events\JobHandledEvent;
use RrConcurrency\Events\JobHandlingErrorEvent;
use RrConcurrency\Events\JobReceivedEvent;
use RrConcurrency\Services\Drivers\Roadrunner\HeadersResolver;
use SLoggerLaravel\Enums\SLoggerTraceStatusEnum;
use SLoggerLaravel\Helpers\SLoggerTraceHelper;
use SLoggerLaravel\Traces\SLoggerTraceIdContainer;
use SLoggerLaravel\Watchers\AbstractSLoggerWatcher;

class SLoggerRrConcurrencyJobWatcher extends AbstractSLoggerWatcher
{
    private array $jobs = [];

    private string $jobType = 'rr-concurrency-job';

    private string $parentTraceIdHeaderName = 'x-parent-trace-id';

    public function register(): void
    {
        $this->app->singleton(
            HeadersResolver::class,
            function () {
                return (new HeadersResolver())
                    ->add(
                        name: $this->parentTraceIdHeaderName,
                        header: fn(SLoggerTraceIdContainer $traceIdContainer) => $traceIdContainer->getParentTraceId()
                    );
            }
        );

        $this->listenEvent(JobReceivedEvent::class, [$this, 'handleJobReceivedEvent']);
        $this->listenEvent(JobHandledEvent::class, [$this, 'handleJobHandledEvent']);
        $this->listenEvent(JobHandlingErrorEvent::class, [$this, 'handleJobHandlingErrorEvent']);
    }

    public function handleJobReceivedEvent(JobReceivedEvent $event): void
    {
        $this->safeHandleWatching(fn() => $this->onHandleJobReceivedEvent($event));
    }

    protected function onHandleJobReceivedEvent(JobReceivedEvent $event): void
    {
        /** @var string|null $parentTraceId */
        $parentTraceId = $event->task->getHeader($this->parentTraceIdHeaderName)[0] ?? null;

        $traceId = $this->processor->startAndGetTraceId(
            type: $this->jobType,
            data: [
                'payload' => $event->task->getPayload(),
            ],
            customParentTraceId: $parentTraceId
        );

        $this->jobs[$event->task->getId()] = [
            'trace_id' => $traceId,
            'started_at' => now(),
        ];
    }

    public function handleJobHandledEvent(JobHandledEvent $event): void
    {
        $this->safeHandleWatching(fn() => $this->onHandleJobHandledEvent($event));
    }

    protected function onHandleJobHandledEvent(JobHandledEvent $event): void
    {
        $taskId = $event->task->getId();

        $jobData = $this->jobs[$taskId] ?? null;

        if (!$jobData) {
            return;
        }

        $traceId = $jobData['trace_id'];

        /** @var Carbon $startedAt */
        $startedAt = $jobData['started_at'];

        $this->processor->stop(
            traceId: $traceId,
            status: SLoggerTraceStatusEnum::Success->value,
            duration: SLoggerTraceHelper::calcDuration($startedAt)
        );

        unset($this->jobs[$taskId]);
    }

    public function handleJobHandlingErrorEvent(JobHandlingErrorEvent $event): void
    {
        $this->safeHandleWatching(fn() => $this->onHandleJobHandlingErrorEvent($event));
    }

    protected function onHandleJobHandlingErrorEvent(JobHandlingErrorEvent $event): void
    {
        $taskId = $event->task->getId();

        $jobData = $this->jobs[$taskId] ?? null;

        if (!$jobData) {
            return;
        }

        $traceId = $jobData['trace_id'];

        /** @var Carbon $startedAt */
        $startedAt = $jobData['started_at'];

        $this->processor->stop(
            traceId: $traceId,
            status: SLoggerTraceStatusEnum::Failed->value,
            duration: SLoggerTraceHelper::calcDuration($startedAt)
        );

        unset($this->jobs[$taskId]);
    }
}
