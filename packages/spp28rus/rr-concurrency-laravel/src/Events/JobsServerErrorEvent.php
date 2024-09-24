<?php

namespace RrConcurrency\Events;

use Illuminate\Foundation\Application;
use Throwable;

class JobsServerErrorEvent
{
    public function __construct(
        public Application $app,
        public Throwable $exception
    ) {
    }
}