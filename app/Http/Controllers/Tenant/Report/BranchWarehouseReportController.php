<?php

namespace App\Http\Controllers\Tenant\Report;

use App\Exports\BranchOrWarehouseReportExport;
use App\Http\Controllers\Controller;
use App\Services\Tenant\Report\BranchOrWarehouseReportService;
use Maatwebsite\Excel\Facades\Excel;

class BranchWarehouseReportController extends Controller
{

    public function __construct(BranchOrWarehouseReportService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->branchOrWarehouseReport();
    }

    public function exportBranchOrWarehouseReport()
    {
        $response = Excel::download(new BranchOrWarehouseReportExport, 'branch_or_warehouse_report.xlsx',\Maatwebsite\Excel\Excel::XLSX);
        if (ob_get_contents()) ob_end_clean();
        return $response;
    }


}
