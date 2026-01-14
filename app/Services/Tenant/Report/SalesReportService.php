<?php

namespace App\Services\Tenant\Report;

use App\Filters\Tenant\OrderFilter;
use App\Models\Tenant\Order\Order;
use App\Services\Tenant\TenantService;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportService extends TenantService
{
    public $orderFilter;

    public function __construct(Order $order,OrderFilter $orderFilter)
    {
        $this->model = $order;
        $this->orderFilter = $orderFilter;
    }

    public function salesSummary()
    {
        $salesSummary = $this->model->query()
            ->filters($this->orderFilter)
            ->branchOrWarehouse(request()->branch_or_warehouse_id);

        $totalSalesAmount = $salesSummary->sum('grand_total');
        $totalDiscount = $salesSummary->sum('discount');
        $totalTax = $salesSummary->sum('total_tax');
        $totalPaid = $salesSummary->sum('paid_amount');
        $totalDue = $salesSummary->sum('due_amount');

        return [
            'totalSalesAmount' => $totalSalesAmount,
            'totalDiscount' => $totalDiscount,
            'totalTax' => $totalTax,
            'totalPaid' => $totalPaid,
            'totalDue' => $totalDue
        ];
    }

    public function download($batch = 0)
    {
        $export_count = config('excel.exports.chunk_size');

        $skip = ($export_count * $batch) - $export_count;

        $data = $this->mapper();

        $relation = ['createdBy', 'branchOrWarehouse:id,name,type',
            'createdBy:id,first_name,last_name',
            'customer:id,first_name,last_name',
            'cashRegister:id,name',
            'status'
        ];

        $orders = getChunk($skip, $export_count, $this->model, $data, $relation, request('branch_or_warehouse_id'));

        $title = __t('sales_report');

        $response = Excel::download(exportBuilder
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
            __t('invoice_number'),
            __t('ordered_at'),
            __t('branch_warehouse'),
            __t('cash_counter'),
            __t('customer_name'),
            __t('sold_by'),
            __t('total_tax'),
            __t('discount'),
            __t('due_amount'),
            __t('paid_amount'),
            __t('sub_total'),
            __t('grand_total'),
        ];
    }

    public function mapper()
    {
        return fn($order) => [
            'invoice_number' => $order->invoice_number,
            'ordered_at' => $order->ordered_at,
            'branch_warehouse' => $order->branchOrWarehouse->name,
            'cash_counter' => $order->cashRegister->name,
            'customer' => $order->customer->full_name,
            'sold_by' => $order->createdBy->full_name,
            'total_tax' => $order->total_tax,
            'discount' => $order->discount,
            'due_amount' => $order->due_amount,
            'paid_amount' => $order->paid_amount,
            'sub_total' => $order->sub_total,
            'grand_total' => $order->grand_total,
        ];
    }


}
