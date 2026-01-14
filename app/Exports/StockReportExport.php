<?php

namespace App\Exports;

use App\Models\Pos\Inventory\Stock\Stock;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockReportExport implements WithHeadings, FromQuery, WithMapping
{
    use Exportable;

    public function query(): \Illuminate\Database\Eloquent\Relations\Relation|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        return Stock::query()
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->with('variant', 'branchOrWarehouse');
    }

    public function map($row): array
    {
        return [
            $row->variant->name ?? null,
            $row->variant->upc ?? null,
            $row->branchOrWarehouse->name ?? null,
            $row->available_qty ?? null,
            $row->avg_purchase_price ?? null,
            $row->variant->selling_price ?? null,
        ];
    }

    public function headings(): array
    {
        return [
            __t('variant_name'),
            __t('upc'),
            __t('branch_or_warehouse'),
            __t('available_qty'),
            __t('avg_purchase_price'),
            __t('selling_price'),
        ];
    }


}
