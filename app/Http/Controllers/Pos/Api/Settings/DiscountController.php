<?php

namespace App\Http\Controllers\Pos\Api\Settings;

use App\Filters\Pos\Settings\DiscountFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Discount\DiscountRequest;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Tenant\Discount\Discount;
use App\Services\Tenant\Setting\DiscountSetting\DiscountSettingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function __construct(DiscountSettingService $service, DiscountFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->branchOrWarehouse(request('branch_or_warehouse_id'))
            ->with('discountProducts', 'status')
            ->withCount('discountProducts')
            ->paginate(request()->get('per_page'), 15);
    }

    public function store(DiscountRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->service
                ->setAttributes($request->all())
                ->storeDiscount()
                ->storeDiscountProduct();
            DB::commit();
            return created_responses('discount');
        } catch (\Exception $exception) {
            DB::rollBack();
            return failed_responses($exception->getMessage());
        }
    }

    public function show(Discount $discount_list): Discount
    {
        return $discount_list->load('discountProducts', 'discountProducts.variant', 'discountProducts.variant.stock');
    }

    public function update(DiscountRequest $request, Discount $discount_list)
    {
        try {
            $this->service
                ->setModel($discount_list)
                ->setAttributes($request->all())
                ->updateDiscount()
                ->updateDiscountProduct();
            return created_responses('discount');
        } catch (\Exception $exception) {
            return failed_responses($exception->getMessage());
        }
    }

    public function destroy(Discount $discount_list)
    {
        $discount_list->discountProducts()->delete();
        $discount_list->delete();
        return deleted_responses('discount');
    }

    public function discountProducts($branch)
    {
        return $this->service->discountVariantProducts($branch);
    }

    public function discountProductShow($branchId, $variantId)
    {
        $variantProduct = Variant::query()->where('id', $variantId)
            ->with(['stock',
                'discountProduct.discount:id,name,branch_or_warehouse_id,discount_type,type,value,start_at,end_at,status_id'
            ])
            ->whereHas('stock', function (Builder $builder) use ($branchId) {
                $builder->where('branch_or_warehouse_id', $branchId);
            })->first();
        return $variantProduct != null ? $variantProduct : null;
    }

    public function discountStatusChange(Discount $discount)
    {
        $this->service
            ->setModel($discount)
            ->changeStatus();
        return updated_responses('status');
    }

}
