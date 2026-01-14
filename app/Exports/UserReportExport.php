<?php

namespace App\Exports;

use App\Models\Core\Auth\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserReportExport implements WithHeadings, FromCollection
{
    public function collection()
    {
        $users = User::query()->withCount(['orders', 'lots', 'returnOrders', 'internalTransfers', 'adjustments'])->get();

        return $users->map(function ($data) {
            return [
                'name' => $data->full_name,
                'orders_count' => $data->orders_count,
                'lots_count' => $data->lots_count,
                'return_orders_count' => $data->return_orders_count,
                'internal_transfers_count' => $data->internal_transfers_count,
                'adjustments_count' => $data->adjustments_count,
            ];
        });
    }


    public function headings(): array
    {
        return [
            __t('name'),
            __t('total_sales'),
            __t('total_purchase'),
            __t('total_sale_return'),
            __t('total_internal_transfer'),
            __t('total_adjustment'),
        ];
    }
}
