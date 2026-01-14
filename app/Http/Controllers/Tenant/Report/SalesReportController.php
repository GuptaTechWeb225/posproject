<?php

namespace App\Http\Controllers\Tenant\Report;

use App\Filters\Tenant\OrderFilter;
use App\Http\Controllers\Controller;
use App\Services\Tenant\Report\SalesReportService;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public $filter;

    public function __construct(SalesReportService $service, OrderFilter $orderFilter)
    {
        $this->service = $service;
        $this->filter = $orderFilter;
    }

    public function index()
    {
        return $this->service->query()
            ->filters($this->filter)
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->with([
                'branchOrWarehouse:id,name,type',
                'createdBy:id,first_name,last_name',
                'customer:id,first_name,last_name',
                'cashRegister:id,name',
                'status',
                'transactions:id,payment_method_id,transactionable_id',
                'transactions.paymentMethod:id,name,alias',
            ])
            ->paginate(request('per_page', 5));
    }

    public function salesSummary(): array
    {
        return $this->service->salesSummary();
    }

    public function exportSalesReport(Request $request)
    {
        $count = $this->service->query();

        $count = $count->count();
        $export_count = config('excel.exports.chunk_size');
        if ($count >= $export_count) {
            return view('tenant.report.sales.chunks', [
                'item_per_sheet' => $export_count,
                'total_sheet_number' => $count / $export_count
            ]);
        }
        return $this->downloadChunkData();
    }

    public function downloadChunkData()
    {
        return $this->service->download(0);
    }
}
