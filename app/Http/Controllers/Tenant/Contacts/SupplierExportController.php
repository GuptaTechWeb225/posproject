<?php


namespace App\Http\Controllers\Tenant\Contacts;

use App\Http\Controllers\Controller;
use App\Services\Tenant\Export\SupplierExportService;

class SupplierExportController extends Controller
{

    public function __construct(SupplierExportService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $count = $this->service->count();
        $export_count = config('excel.exports.chunk_size');
        if ($count >= $export_count) {
            return view('tenant.contact.supplier.chunks', [
                'export_count' => $export_count,
                'customer_per_sheet' => $count / $export_count
            ]);
        }
        return $this->download(0);
    }

    public function download($skip = 0)
    {
        return $this->service->download($skip);
    }
    

}