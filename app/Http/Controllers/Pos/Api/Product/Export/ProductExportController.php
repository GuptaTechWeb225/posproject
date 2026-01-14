<?php

namespace App\Http\Controllers\Pos\Api\Product\Export;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Excel;

class ProductExportController extends Controller
{
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function exportsAllProducts()
    {
        $response = (new ProductsExport)->download('products.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        if (ob_get_contents()) ob_end_clean();

        return $response;
    }
}
