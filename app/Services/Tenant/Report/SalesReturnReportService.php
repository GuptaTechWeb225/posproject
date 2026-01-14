<?php

namespace App\Services\Tenant\Report;

use App\Filters\Tenant\SalesReturnFilter;
use App\Models\Tenant\Order\ReturnOrder;
use App\Services\Tenant\TenantService;
use Maatwebsite\Excel\Facades\Excel;

class SalesReturnReportService extends TenantService
{
    public $returnFilter;

    public function __construct(ReturnOrder $returnOrder, SalesReturnFilter $filter)
    {
        $this->model = $returnOrder;
        $this->returnFilter = $filter;
    }


    public function salesReturnSummary()
    {
        $salesSummary = $this->model->query()
            ->filters($this->returnFilter);

        $totalSalesAmount = $salesSummary->sum('sub_total');
        $totalDiscount = $salesSummary->sum('discount');

        return [
            'totalSalesAmount' => $totalSalesAmount,
            'totalDiscount' => $totalDiscount,
        ];
    }

    public function download($batch = 0)
    {
        $export_count = config('excel.exports.chunk_size');

        $skip = ($export_count * $batch) - $export_count;

        $data = $this->mapper();

        $relation = ['createdBy', 'branchOrWarehouse:id,name,type',
            'createdBy:id,first_name,last_name',
            'customer:id,first_name,last_name'
        ];

        $orders = getChunk($skip, $export_count, $this->model, $data, $relation,request('branch_or_warehouse_id'));

        $title = __t('sales_return_report');

        $response =  Excel::download(exportBuilder
        (
            $orders,
            $this->getHeadings(),
            $title
        ), "$title-$batch.xlsx", \Maatwebsite\Excel\Excel::XLSX
        );

        if (ob_get_contents()) ob_end_clean();
        return $response;
    }

    public function getHeadings()
    {
        return [
            __t('branch_warehouse'),
            __t('invoice_number'),
            __t('customer_name'),
            __t('return_by'),
            __t('returned_at'),
            __t('discount'),
            __t('sub_total'),
        ];
    }

    public function mapper()
    {
        return fn($order) => [
            'branch_warehouse' => $order->branchOrWarehouse->name,
            'invoice_number' => $order->invoice_number,
            'customer' => $order->customer->full_name,
            'created_by' => $order->createdBy->full_name,
            'returned_at' => $order->returned_at,
            'discount' => $order->discount,
            'sub_total' => $order->sub_total,
        ];
    }

}
