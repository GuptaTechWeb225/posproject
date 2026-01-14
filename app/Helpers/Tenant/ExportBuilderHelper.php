<?php

use App\Exports\ExportBuilder;

if (!function_exists('exportBuilder')) {
    function exportBuilder($collection, $headings, $title)
    {
        return (new ExportBuilder())
            ->setHeadings($headings)
            ->setTitle($title)
            ->setCollection($collection)
            ->get();
    }
}

if (!function_exists('getChunk')) {
    function getChunk($skip, $take, $model, $map, array $relation = null, $branchOrWarehouse = null)
    {
        $take = $take ?? 0;
        return $model::with($relation)
            ->when($branchOrWarehouse, fn($model) => $model->branchOrWarehouse($branchOrWarehouse))
            ->skip($skip)
            ->when($take, fn($model) => $model->take($take))
            ->get()
            ->map($map);
    }
}

