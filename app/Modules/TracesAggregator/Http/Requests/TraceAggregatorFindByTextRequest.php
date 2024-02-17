<?php

namespace App\Modules\TracesAggregator\Http\Requests;

use App\Modules\TracesAggregator\Enums\TraceDataFilterCompNumericTypeEnum;
use App\Modules\TracesAggregator\Enums\TraceDataFilterCompStringTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class TraceAggregatorFindByTextRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text'                        => [
                'sometimes',
                'string',
                'nullable',
                'min:1',
            ],
            'logging_from'                => [
                'sometimes',
                'date',
            ],
            'logging_to'                  => [
                'sometimes',
                'date',
            ],
            'data'                        => [
                'sometimes',
                'array',
            ],
            'data.filter'                 => [
                'sometimes',
                'array',
            ],
            'data.filter.*.field'         => [
                'required',
                'string',
            ],
            'data.filter.*.null'          => [
                'sometimes',
                'bool',
            ],
            'data.filter.*.numeric'       => [
                'sometimes',
                'array',
            ],
            'data.filter.*.numeric.value' => [
                'sometimes',
                'numeric',
            ],
            'data.filter.*.numeric.comp'  => [
                'sometimes',
                'string',
                'in:' . implode(
                    ',',
                    array_map(
                        fn(TraceDataFilterCompNumericTypeEnum $enum) => $enum->value,
                        TraceDataFilterCompNumericTypeEnum::cases()
                    )
                ),
            ],
            'data.filter.*.string'        => [
                'sometimes',
                'array',
            ],
            'data.filter.*.string.value'  => [
                'sometimes',
                'string',
            ],
            'data.filter.*.string.comp'   => [
                'sometimes',
                'string',
                'in:' . implode(
                    ',',
                    array_map(
                        fn(TraceDataFilterCompStringTypeEnum $enum) => $enum->value,
                        TraceDataFilterCompStringTypeEnum::cases()
                    )
                ),
            ],
            'data.filter.*.boolean'       => [
                'sometimes',
                'array',
            ],
            'data.filter.*.boolean.value' => [
                'sometimes',
                'bool',
            ],
        ];
    }
}
