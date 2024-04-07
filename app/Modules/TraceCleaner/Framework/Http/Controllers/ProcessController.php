<?php

namespace App\Modules\TraceCleaner\Framework\Http\Controllers;

use App\Modules\TraceCleaner\Domain\Actions\FindProcessesAction;
use App\Modules\TraceCleaner\Domain\Actions\FindSettingByIdAction;
use App\Modules\TraceCleaner\Framework\Http\Resources\ProcessResource;
use Ifksco\OpenApiGenerator\Attributes\OaListItemTypeAttribute;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

readonly class ProcessController
{
    public function __construct(
        private FindSettingByIdAction $findSettingByIdAction,
        private FindProcessesAction $findProcessesAction
    ) {
    }

    #[OaListItemTypeAttribute(ProcessResource::class)]
    public function index(int $settingId): AnonymousResourceCollection
    {
        abort_if(
            !$this->findSettingByIdAction->handle($settingId),
            Response::HTTP_NOT_FOUND
        );

        return ProcessResource::collection(
            $this->findProcessesAction->handle(
                page: 1,
                settingId: $settingId
            )
        );
    }
}