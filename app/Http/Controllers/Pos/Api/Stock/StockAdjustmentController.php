<?php

namespace App\Http\Controllers\Pos\Api\Stock;

use App\Filters\Pos\Inventory\AdjustmentFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\Inventory\Adjustment\AdjustmentRequest;
use App\Models\Pos\Inventory\Adjustment\Adjustment;
use App\Services\Pos\Inventory\Adjustment\AdjustmentService;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function __construct(AdjustmentService $service, AdjustmentFilter $filter)
    {
        $this->filter = $filter;
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->service
            ->select('id','branch_or_warehouse_id', 'adjustment_date', 'created_by', 'reference_no', 'note')
            ->branchOrWarehouse(request()->branch_or_warehouse_id)
            ->filters($this->filter)
            ->with([
                'createdBy:id,first_name,last_name',
                'branchOrWarehouse:id,name,type',
                'adjustmentVariants'
            ])
            ->latest('id')
            ->paginate(
                request('per_page', 10)
            );
    }


    public function store(AdjustmentRequest $request)
    {
        DB::transaction( function () use ($request){
            $this->service
                ->setAttrs($request->all())
                ->storeAdjustmentData()
                ->storeAdjustmentVariantData()
                ->updateStock();
        });

        return created_responses('adjustment');
    }


    public function show(Adjustment $stock_adjustment): Adjustment
    {
        return $stock_adjustment->load($this->service->relations());
    }


    public function update(AdjustmentRequest $request, Adjustment $stock_adjustment)
    {
        DB::transaction(function () use ($request, $stock_adjustment){
            $this->service
                ->setModel($stock_adjustment)
                ->setAttrs($request->all())
                ->updateAdjustmentData(
                    $request->only(
                        'branch_or_warehouse_id', 'adjustment_date', 'created_by',
                        'reference_no', 'note'
                    )
                )
                ->updateAdjustmentVariantDataAndUpdateStock();
        });

        return updated_responses('stock_adjustment');
    }


    public function destroy(Adjustment $stock_adjustment)
    {
        DB::transaction(function () use ($stock_adjustment){
            $this->service
                ->setModel($stock_adjustment)
                ->deleteAdjustmentFromStock()
                ->deleteAdjustmentVariant()
                ->deleteAdjustment()
            ;
        });

        return deleted_responses('adjustment');
    }
}
