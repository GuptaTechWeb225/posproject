<?php

namespace App\Http\Controllers\Tenant\Report;

use App\Exports\ProfitLossExport;
use App\Http\Controllers\Controller;
use App\Services\Tenant\Report\ProfitLossService;
use Maatwebsite\Excel\Facades\Excel;

class ProfitLossReportController extends Controller
{
    public function __construct(ProfitLossService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->profitLoss();
    }


    public function exportProfitLoss()
    {
        $response =  Excel::download(new ProfitLossExport(), 'profit_loss_report.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        // if (ob_get_contents()) ob_end_clean();
        return $response;
    }
}
