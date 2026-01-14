<?php

namespace App\Http\Controllers\Pos\Api\Report;

use App\Exports\LotReportExport;
use App\Filters\Pos\Inventory\LotFilter;
use App\Http\Controllers\Controller;
use App\Models\Pos\Inventory\Lot\Lot;
use App\Services\Pos\Inventory\Stock\StockService;
use Maatwebsite\Excel\Facades\Excel;

class LotReportController extends Controller
{

    public $filter;
    public $model;
    public StockService $stockService;
    public $excel;

    public function __construct(Lot $lot, LotFilter $lotFilter, StockService $stockService, Excel $excel)
    {
        $this->model = $lot;
        $this->filter = $lotFilter;
        $this->stockService = $stockService;
        $this->excel = $excel;
    }

    public function index()
    {
        return $this->model->query()
            ->select('id','created_at','reference_no','supplier_id','status_id', 'branch_or_warehouse_id', 'discount_amount', 'created_by', 'other_charge', 'total_amount')
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->with(
                'branchOrWarehouse:id,name,type',
                'lotVariants',
                'createdBy:id,first_name,last_name',
                'supplier:id,name',
                'status:id,name,class,type',
            )
            ->withSum('lotVariants as total_unit', 'unit_quantity')
            ->filters($this->filter)
            ->latest('id')
            ->paginate(
                request('per_page', 15)
            );
    }


    public function purchaseSummary()
    {
        $purchaseSummary = $this->model->query()
            ->filters($this->filter);

        if(request()->branch_or_warehouse_id){
            $purchaseSummary = $purchaseSummary->branchOrWarehouse(request()->branch_or_warehouse_id);
        }

        $total_unit_quantity = 0;
        foreach ($purchaseSummary->get() as $lot){
            foreach ($lot->lotVariants as $lotVariant) {
                $total_unit_quantity += $lotVariant->unit_quantity;
            }
        }

        $totalDiscount = $purchaseSummary->sum('discount_amount');
        $totalOtherCharges = $purchaseSummary->sum('other_charge');
        $total_purchasePrice = $purchaseSummary->sum('total_amount');

        return [
            'totalPurchaseAmount' => $total_purchasePrice,
            'totalDiscount' => $totalDiscount,
            'totalOtherCharges' => $totalOtherCharges,
            'totalPaid' => $total_purchasePrice,
            'total_unit_quantity' => $total_unit_quantity,
        ];
   }

    public function exportAllLotByBranchOrWarehouse()
    {
        $response =  (new LotReportExport())->download('purchase.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        if (ob_get_contents()) ob_end_clean();
        return $response;
    }

}
