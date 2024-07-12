<?php

namespace App\Modules\Tools\Framework\Services\Objects;

readonly class ToolLinksObject
{
    public function __construct(
        public string $name,
        public string $url
    ) {
    }
}
