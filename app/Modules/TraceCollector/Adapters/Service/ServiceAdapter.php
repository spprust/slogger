<?php

namespace App\Modules\TraceCollector\Adapters\Service;

use App\Modules\Service\Domain\Actions\GetCurrentServiceAction;
use App\Modules\Service\Framework\Http\Middlewares\AuthServiceMiddleware;
use App\Modules\TraceCollector\Domain\Entities\Objects\ServiceObject;

readonly class ServiceAdapter
{
    public function __construct(private GetCurrentServiceAction $getCurrentServiceAction)
    {
    }

    public function getAuthMiddleware(): string
    {
        return AuthServiceMiddleware::class;
    }

    public function getService(): ?ServiceObject
    {
        $service = $this->getCurrentServiceAction->handle();

        if (!$service) {
            return null;
        }

        return new ServiceObject(
            id: $service->id,
            name: $service->name
        );
    }
}
