<?php

namespace App\Modules\Trace\Repositories;

use App\Models\Traces\TraceTree;
use App\Modules\Trace\Repositories\Interfaces\TraceTreeRepositoryInterface;
use MongoDB\Model\BSONDocument;

class TraceTreeRepository implements TraceTreeRepositoryInterface
{
    private int $maxDepthForFindParent = 100;

    public function findTraceIdsInTreeByParentTraceId(string $traceId): array
    {
        $childrenAggregation = TraceTree::collection()
            ->aggregate(
                [
                    [
                        '$graphLookup' => [
                            'from'             => 'traceTreesView',
                            'startWith'        => '$tid',
                            'connectFromField' => 'tid',
                            'connectToField'   => 'ptid',
                            'as'               => 'children',
                            'maxDepth'         => $this->maxDepthForFindParent,
                        ],
                    ],
                    [
                        '$project' => [
                            'tid'      => 1,
                            'childIds' => [
                                '$concatArrays' => [
                                    [
                                        '$tid',
                                    ],
                                    [
                                        '$map' => [
                                            'input' => '$children',
                                            'as'    => 'children',
                                            'in'    => '$$children.tid',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        '$match' => [
                            'tid' => $traceId,
                        ],
                    ],
                    [
                        '$unwind' => [
                            'path' => '$childIds',
                        ],
                    ],
                    [
                        '$match' => [
                            'childIds' => [
                                '$ne' => $traceId,
                            ],
                        ],
                    ],
                ]
            );

        return collect($childrenAggregation)
            ->map(fn(BSONDocument $item) => $item['childIds'])
            ->toArray();
    }

    public function findParentTraceId(string $traceId): ?string
    {
        /** @var array|null $trace */
        $trace = TraceTree::query()
            ->select([
                'tid',
                'ptid',
            ])
            ->where('tid', $traceId)
            ->toBase()
            ->first();

        if (!$trace) {
            return null;
        }

        $parentTrace = $trace;

        if ($trace['ptid']) {
            $index = 0;

            while (++$index <= $this->maxDepthForFindParent) {
                if (!$parentTrace['ptid']) {
                    break;
                }

                /** @var array|null $currentParentTrace */
                $currentParentTrace = TraceTree::query()
                    ->select([
                        'tid',
                        'ptid',
                    ])
                    ->where('tid', $parentTrace['ptid'])
                    ->toBase()
                    ->first();

                if (!$currentParentTrace) {
                    break;
                }

                $parentTrace = $currentParentTrace;
            }
        }

        return $parentTrace['tid'];
    }
}
