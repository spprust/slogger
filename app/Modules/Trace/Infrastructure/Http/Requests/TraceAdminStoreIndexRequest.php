<?php

namespace App\Modules\Trace\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TraceAdminStoreIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page'         => [
                'required',
                'int',
                'min:1',
            ],
            'version'      => [
                'required',
                'int',
                'min:1',
            ],
            'search_query' => [
                'sometimes',
                'nullable',
                'string',
            ],
        ];
    }
}
