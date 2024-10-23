<?php

namespace App\Modules\Trace\Contracts\Repositories;

use App\Modules\Trace\Entities\Store\TraceAdminStoreObject;
use App\Modules\Trace\Entities\Store\TraceAdminStoresPaginationObject;

interface TraceAdminStoreRepositoryInterface
{
    public function create(
        string $title,
        int $storeVersion,
        string $storeDataHash,
        string $storeData
    ): TraceAdminStoreObject;

    public function find(
        int $page,
        int $perPage,
        int $version,
        ?string $searchQuery = null
    ): TraceAdminStoresPaginationObject;

    public function delete(string $id): bool;
}