<?php

namespace App\Exports;

use App\Models\Pos\Inventory\Lot\Lot;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LotReportExport implements WithHeadings, FromQuery, WithMapping
{
    use Exportable;


    public function query(): \Illuminate\Database\Eloquent\Relations\Relation|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        return  Lot::query()
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->with('createdBy', 'supplier', 'branchOrWarehouse');
    }

    public function map($row): array
    {
        return [
            $row->reference_no ?? null,
            $row->branchOrWarehouse->name ?? null,
            $row->supplier->name ?? null,
            $row->status->name ?? null,
            $row->lotVariants->sum('unit_quantity') ?? null,
            $row->other_charge ?? null,
            $row->discount_amount ?? null,
            $row->createdBy->full_name ?? null,
            $row->total_amount ?? null,

        ];
    }


    public function headings(): array
    {
        return [
            __t('reference_no'),
            __t('branch_or_warehouse'),
            __t('supplier'),
            __t('status'),
            __t('total_items'),
            __t('other_charge'),
            __t('discount_amount'),
            __t('created_by'),
            __t('grand_total'),
        ];
    }


}
