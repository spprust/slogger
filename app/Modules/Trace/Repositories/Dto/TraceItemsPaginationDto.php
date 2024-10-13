<?php

namespace App\Modules\Trace\Repositories\Dto;

use App\Modules\Common\Entities\PaginationInfoObject;

readonly class TraceItemsPaginationDto
{
    /**
     * @param TraceDetailDto[] $items
     */
    public function __construct(
        public array $items,
        public PaginationInfoObject $paginationInfo
    ) {
    }
}
