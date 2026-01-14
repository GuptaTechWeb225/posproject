<?php

namespace App\Http\Controllers\Tenant\Report;

use App\Http\Controllers\Controller;
use App\Services\Tenant\Report\TopSellingProductService;
use Illuminate\Http\Request;

class TopSellingProductReportController extends Controller
{
    public function __construct(TopSellingProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->topSellingProducts();
    }

    public function topSellingProductsExport(Request $request)
    {

        $count = $this->service->topSellingQuery();

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
