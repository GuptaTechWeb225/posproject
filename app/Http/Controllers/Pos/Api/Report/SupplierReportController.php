<?php

namespace App\Http\Controllers\Pos\Api\Report;

use App\Exports\SupplierReportExport;
use App\Filters\Tenant\SupplierFilter;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Supplier\Supplier;
use Maatwebsite\Excel\Facades\Excel;

class SupplierReportController extends Controller
{
    public $excel;

    public function __construct(Supplier $supplier, SupplierFilter $supplierFilter, Excel $excel)
    {
        $this->model = $supplier;
        $this->filter = $supplierFilter;
        $this->excel = $excel;
    }

    public function index()
    {
        $branch_or_warehouse_id = request()->branch_or_warehouse_id;

        return $this->model->query()
            ->filters($this->filter)
            ->select('id','name','supplier_no','created_by','status_id')
            ->with([
                'status:id,name,class,type',
                'contacts'
            ])
            ->withWhereHas('lots',function ($query) use ($branch_or_warehouse_id){
                $branch_or_warehouse_id === 'null' ?  $query->where('branch_or_warehouse_id', '!=', null)->with(['branchOrWarehouse:id,name,type'])
                    : $query->where('branch_or_warehouse_id', $branch_or_warehouse_id)->with(['branchOrWarehouse:id,name,type']);
            })
            ->withSum(
                    ['lots as total_purchase' => function($query) use($branch_or_warehouse_id){
                        $branch_or_warehouse_id === 'null' ? '' : $query->where('branch_or_warehouse_id', $branch_or_warehouse_id);

                    }],
                    'total_amount'
                )
            ->latest('id')
            ->paginate(
                request('per_page', 15)
            );
    }

    public function exportAllSupplierByBranchOrWarehouse()
    {
        $response =  (new SupplierReportExport())->download('supplier.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        if (ob_get_contents()) ob_end_clean();
        return $response;
    }



}
