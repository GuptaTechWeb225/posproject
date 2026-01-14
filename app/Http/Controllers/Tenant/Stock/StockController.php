<?php

namespace App\Http\Controllers\Tenant\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Stock\StockRequest;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Services\Tenant\Stock\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function __construct(StockService $services)
    {
        $this->service = $services;
    }

    public function store(StockRequest $request)
    {
        DB::transaction(function () use ($request) {

             $this->service
                 ->setAttributes($request->all())
                 ->storeStock()
                 ->storeStockQuantities();
        });

        return created_responses('stock');
    }

    public function show(Stock $stock): Stock
    {
        return $stock;
    }

    public function update(Request $request, Stock $stock)
    {
        $this->service
            ->setModel($stock)
            ->setAttributes(
                $request->only(
                    'lot_id',
                    'variant_id',
                    'purchase_price',
                    'expire_date',
                    'manufacturing_date',
                    'bar_code',
                    'sku',
                    'tax_id',
                    'tax_type',
                    'created_by',
                    'tenant_id',
                )
            )
            ->save();

        return updated_responses('stock');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();

        return deleted_responses('stock');
    }
}
