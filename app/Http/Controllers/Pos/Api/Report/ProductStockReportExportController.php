<?php

namespace App\Http\Controllers\Pos\Api\Report;

use App\Exports\StockReportExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Excel;

class ProductStockReportExportController extends Controller
{
    public $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function exportAllByBranchOrWarehouse()
    {
        $response =  (new StockReportExport())->download('stock.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        if (ob_get_contents()) ob_end_clean();
        return $response;
    }

}
