<?php

namespace App\Exports;

use App\Http\Controllers\Tenant\Report\CashCounterReportController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CashCounterExport implements FromCollection, WithHeadings
{


    public function collection()
    {
        $data = resolve(CashCounterReportController::class)->cashCounterQuery()->get();

        return $data->map(function ($row) {
            return [
                'name' => $row->cashRegister->name,
                'opened_by' => $row->openedBy->full_name,
                'closed_by' => $row->closedBy->full_name,
                'sold_by' => $row->soldBy->full_name,
                'opening_balance' => $row->opening_balance,
                'cash_sales' => $row->cash_sales,
                'opening_time' => $row->opening_time,
                'closing_time' => $row->closing_time,
                'closing_balance' => $row->closing_balance,
                'difference' => $row->difference,
            ];
        });
    }


    public function headings(): array
    {
        return [
            __t('name'),
            __t('sold_by'),
            __t('opened_by'),
            __t('closed_by'),
            __t('opening_balance'),
            __t('cash_sales'),
            __t('opening_time'),
            __t('closing_time'),
            __t('closing_time'),
            __t('closing_balance'),
            __t('difference'),
        ];
    }

}
