<?php

namespace App\Http\Controllers\Tenant\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Stock\AdjustStockRequest;
use App\Services\Tenant\Stock\StockService;
use Illuminate\Support\Facades\DB;


class StockAdjustmentController extends Controller
{
    public function __construct(StockService $services)
    {
        $this->service = $services;
    }

    public function adjustStock(AdjustStockRequest $request)
    {
        DB::transaction(function () use ($request) {

            $this->service
                ->setAttributes($request->all())
                ->storeStock()
                ->storeStockQuantities();
        });

        return updated_responses('stock_adjustment');
    }
}
