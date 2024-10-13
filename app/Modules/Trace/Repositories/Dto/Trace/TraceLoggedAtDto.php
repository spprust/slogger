<?php

namespace App\Modules\Trace\Repositories\Dto\Trace;

use Illuminate\Support\Carbon;

readonly class TraceLoggedAtDto
{
    public function __construct(
        public string $traceId,
        public Carbon $loggedAt
    ) {
    }
}
