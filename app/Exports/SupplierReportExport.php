<?php

namespace App\Exports;

use App\Models\Tenant\Supplier\Supplier;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SupplierReportExport implements WithHeadings, FromQuery, WithMapping
{
    use Exportable;

    public function query()
    {
        $branch_or_warehouse_id = \request()->branch_or_warehouse_id;

        return Supplier::query()
            ->select('id','name','supplier_no','created_by','status_id')
            ->with([
                'status:id,name,class,type',
                'contacts'
            ])
            ->withWhereHas('lots',function ($query) use ($branch_or_warehouse_id){
                $branch_or_warehouse_id === 'null' ?  $query->where('branch_or_warehouse_id', '!=', null)->with(['branchOrWarehouse:id,name,type'])
                    : $query->where('branch_or_warehouse_id', $branch_or_warehouse_id)->with(['branchOrWarehouse:id,name,type']);
            })
            ->withSum(
                ['lots as total_purchase' => function($query) use($branch_or_warehouse_id){
                    $branch_or_warehouse_id === 'null' ? '' : $query->where('branch_or_warehouse_id', $branch_or_warehouse_id);

                }],
                'total_amount'
            );
    }


    public function map($row): array
    {
        return [
            $row->name ?? null,
            $row->supplier_no ?? null,
            $this->mapBranchWarehouses($row->lots),
            $row->status->name ?? null,
            $row->total_purchase ?? null,
        ];
    }

    private function mapBranchWarehouses($orders): string
    {
        $branchWarehouses = '';
        foreach ($orders as $order) {
            $branchWarehouses .= $order->branchOrWarehouse ? $order->branchOrWarehouse->name  . ',' : '';
        }
        return substr($branchWarehouses, 0, -1);
    }

    public function headings(): array
    {
        return [
            __t('name'),
            __t('supplier_no'),
            __t('branch_or_warehouse'),
            __t('status'),
            __t('total_purchase'),
        ];
    }
}
