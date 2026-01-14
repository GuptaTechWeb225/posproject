<?php

namespace App\Exports;

use App\Models\Tenant\Customer\Customer;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerReportExport implements WithHeadings, FromQuery, WithMapping
{
    use Exportable;

    public Customer $model;

    public function query(): \Illuminate\Database\Eloquent\Relations\Relation|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        $branch_or_warehouse_id = \request()->branch_or_warehouse_id;

        return Customer::query()
            ->select('id','customer_group_id','email','tin','first_name', 'last_name')
            ->whereHas('orders', function ($query) use ($branch_or_warehouse_id){
                $branch_or_warehouse_id === 'null' ?  $query->where('branch_or_warehouse_id', '!=', null) : $query->where('branch_or_warehouse_id', $branch_or_warehouse_id);
            })
            ->with([
                'customerGroup:id,name',
                'orders',
                'orders.branchOrWarehouse:id,name,type'
            ])
            ->withSum('orders as total_purchase', 'grand_total')
            ->withSum('orders as total_due', 'due_amount');
    }

    public function map($customer): array
    {
        return [
            $customer->name ?? null,
            $customer->email ?? null,
            $this->mapBranchWarehouses($customer->orders),
            $customer->customerGroup->name ?? null,
            $customer->total_purchase ?? null,
            $customer->total_due ?? null,
            $customer->total_purchase - $customer->total_due ?? null, //total paid
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
            __t('email'),
            __t('branch_or_warehouse'),
            __t('customer_group'),
            __t('total_purchase'),
            __t('total_due'),
            __t('total_paid'),
        ];
    }
}
