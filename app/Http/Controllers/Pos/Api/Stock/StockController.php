<?php

namespace App\Http\Controllers\Pos\Api\Stock;

use App\Filters\Pos\Inventory\StockFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Inventory\StockCollection;
use App\Services\Pos\Inventory\Stock\StockService;

class StockController extends Controller
{
    public function __construct(StockService $services, StockFilter $filter)
    {
        $this->service = $services;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->select('id', 'variant_id', 'avg_purchase_price', 'available_qty', 'branch_or_warehouse_id')
            ->filters($this->filter)
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->with([
                'branchOrWarehouse:id,name,type',
                'variant:id,name,product_id,selling_price,upc',
                'variant.thumbnail',
                'variant.product' => function ($product) {
                    $product->select(['id', 'category_id', 'brand_id', 'product_type'])
                        ->with([
                            'category' => function ($q) {
                                $q->select(['id', 'name']);
                            },
                            'brand' => function ($q) {
                                $q->select(['id', 'name']);
                            },

                        ]);
                },
                'variant.product.thumbnail',
            ])
            ->latest('id')
            ->paginate(
                request('per_page', 10)
            );
    }

    public function getAvailableStock($variant_id, $branch_or_warehouse_id)
    {
        return $this->service
            ->where([
                ['variant_id', $variant_id],
                ['branch_or_warehouse_id', $branch_or_warehouse_id]
            ])
            ->select('id', 'variant_id', 'branch_or_warehouse_id', 'available_qty')
            ->filters($this->filter)
            ->first();
    }

    public function stockOverView($variant_id): StockCollection
    {
        $overView = $this->service
            ->where('variant_id', $variant_id)
            ->select('id', 'variant_id', 'available_qty', 'updated_at', 'branch_or_warehouse_id')
            ->filters($this->filter)
            ->latest('id')
            ->paginate(
                request('per_page', 10)
            );

        return new StockCollection($overView);
    }
}
