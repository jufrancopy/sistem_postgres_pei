<?php

namespace App\Http\Controllers\Admin\Bioestadistica\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait RespondsWithDataTables
{
    /**
     * @param  callable(Builder, string): void  $applySearch
     * @param  array<int, string|null>  $orderColumns  índice DT => columna SQL (null = no ordenable)
     * @param  callable(mixed): array<string, mixed>  $mapRow
     */
    protected function dataTablesJson(
        Request $request,
        Builder $baseQuery,
        callable $applySearch,
        array $orderColumns,
        callable $mapRow,
        string $defaultOrderColumn = 'id',
        string $defaultOrderDir = 'desc'
    ): JsonResponse {
        $draw = max(1, (int) $request->input('draw', 1));
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 25);
        if ($length < 1 || $length > 100) {
            $length = 25;
        }

        $recordsTotal = (clone $baseQuery)->toBase()->getCountForPagination();

        $filtered = clone $baseQuery;
        $search = trim((string) data_get($request->input('search'), 'value', ''));
        if ($search !== '') {
            $applySearch($filtered, $search);
        }

        $recordsFiltered = (clone $filtered)->toBase()->getCountForPagination();

        $order = $request->input('order.0', []);
        $orderIndex = (int) ($order['column'] ?? -1);
        $orderDir = strtolower((string) ($order['dir'] ?? $defaultOrderDir)) === 'asc' ? 'asc' : 'desc';
        $orderColumn = $orderColumns[$orderIndex] ?? null;
        if ($orderColumn) {
            $filtered->reorder()->orderBy($orderColumn, $orderDir);
        } else {
            $filtered->reorder()->orderBy($defaultOrderColumn, $defaultOrderDir);
        }

        $rows = $filtered->skip($start)->take($length)->get()->map($mapRow)->values();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows,
        ]);
    }
}
