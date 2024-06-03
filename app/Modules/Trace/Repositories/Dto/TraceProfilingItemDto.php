<?php

namespace App\Modules\Trace\Repositories\Dto;

readonly class TraceProfilingItemDto
{
    /**
     * @param TraceProfilingDataDto[] $data
     */
    public function __construct(
        public string $raw,
        public string $calling,
        public string $callable,
        public array $data
    ) {
    }
}
