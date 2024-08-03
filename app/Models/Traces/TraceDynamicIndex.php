<?php

namespace App\Models\Traces;

use App\Models\AbstractTraceModel;
use Illuminate\Support\Carbon;

/**
 * @property string $_id
 * @property string $name
 * @property array  $fields
 * @property bool   $inProcess
 * @property bool   $created
 * @property Carbon $actualUntilAt
 * @property Carbon $createdAt
 */
class TraceDynamicIndex extends AbstractTraceModel
{
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = null;

    protected $collection = 'traceDynamicIndexes';

    protected $casts = [
        'inProcess'     => 'bool',
        'created'       => 'bool',
        'actualUntilAt' => 'datetime',
    ];
}
