<?php

namespace App\Exports;

use App\Services\Tenant\Report\ProfitLossService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfitLossExport implements FromCollection,WithHeadings
{

    public function collection()
    {
        $data = resolve(ProfitLossService::class)->profitLossQuery()->get();

        return $data->map(function ($data) {
            return [
                'group_by' => $data->group_by,
                'total_sell_amount' => $data->total_sell_amount,
                'net_profit' => $data->net_profit
            ];
        });
    }


    public function headings(): array
    {
        return [
            __t('group_by'),
            __t('total_sell_amount'),
            __t('net_profit')
        ];
    }
}
