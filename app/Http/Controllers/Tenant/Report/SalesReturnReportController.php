<?php

namespace App\Http\Controllers\Tenant\Report;

use App\Filters\Tenant\SalesReturnFilter;
use App\Http\Controllers\Controller;
use App\Services\Tenant\Report\SalesReturnReportService;
use Illuminate\Http\Request;

class SalesReturnReportController extends Controller
{
    public $filter;

    public function __construct(SalesReturnReportService $service, SalesReturnFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
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
            ])
            ->paginate(request('per_page', 5));
    }

    public function salesReturnSummary(): array
    {
        return $this->service->salesReturnSummary();
    }

    public function exportSalesReturnReport(Request $request)
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
