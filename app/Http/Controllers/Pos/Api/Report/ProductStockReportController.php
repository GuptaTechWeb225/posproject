<?php

namespace App\Http\Controllers\Pos\Api\Report;

use App\Filters\Pos\Inventory\StockFilter;
use App\Http\Controllers\Controller;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Services\Pos\Inventory\Stock\StockService;

class ProductStockReportController extends Controller
{
    public $filter;
    public $model;
    public StockService $stockService;

    public function __construct(Stock $stock, StockFilter $stockFilter)
    {
        $this->model = $stock;
        $this->filter = $stockFilter;
    }

    public function index()
    {
        return $this->model->query()
            ->select('id', 'branch_or_warehouse_id', 'variant_id', 'avg_purchase_price', 'available_qty', 'created_at')
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->with(
                'branchOrWarehouse:id,name,type',
                'variant:id,name,product_id,upc,selling_price,name'
            )
            ->filters($this->filter)
            ->latest('id')
            ->paginate(
                request('per_page', 15)
            );
    }

}
