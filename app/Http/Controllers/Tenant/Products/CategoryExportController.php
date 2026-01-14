<?php

namespace App\Http\Controllers\Tenant\Products;

use App\Http\Controllers\Controller;
use App\Services\Tenant\Export\CategoryExportService;

class CategoryExportController extends Controller
{
    public function __construct(CategoryExportService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $count = $this->service->count();
        $export_count = config('excel.exports.chunk_size');
        if ($count >= $export_count) {
            return view('tenant.product.category.chunks', [
                'item_per_sheet' => $export_count,
                'total_sheet_number' => $count / $export_count
            ]);
        }
        return $this->download(0);
    }

    public function download($skip = 0)
    {
        return $this->service->download($skip);
    }
}
