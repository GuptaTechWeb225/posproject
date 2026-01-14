<?php

namespace App\Http\Controllers\Tenant\Products;

use App\Exceptions\GeneralException;
use App\Filters\Tenant\VariantFilter;
use App\Http\Controllers\Controller;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Tenant\Discount\Discount;
use App\Models\Tenant\Discount\DiscountProduct;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Pos\Inventory\Stock\StockService;
use App\Services\Tenant\Product\VariantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VariantController extends Controller
{
    public StockService $stockService;

    public function __construct(VariantService $service, VariantFilter $filter, StockService $stockService)
    {
        $this->service = $service;
        $this->filter = $filter;
        $this->stockService = $stockService;
    }

    public function variantList($productId = null): LengthAwarePaginator
    {
        return $this->service
            ->filters($this->filter)
            ->select('id', 'product_id', 'upc', 'selling_price', 'name', 'tax_id', 'stock_reminder_quantity', 'status_id', 'description')
            ->with($this->service->relations())
            ->orderByDesc('id')
            ->where('product_id', $productId)
            ->paginate(
                request('per_page', 10)
            );
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $variant = $this->service
                ->save(
                    $request->only('product_id', 'name', 'sku', 'description', 'low_stock_quantity', 'warranty_duration', 'warranty_duration_type', 'tax_id', 'created_by', 'tenant_id')
                );

            $this->service
                ->setModel($variant)
                ->setAttrs($request->only('photos'))
                ->storeVariantGallery();
        });

        return created_responses('variant');
    }

    public function show(Variant $variant): Variant
    {
        return $variant->load($this->service->relations());
    }

    public function update(Request $request, Variant $variant)
    {
        $request->validate([
            'stock_reminder_quantity'=> 'required',
            'tax_id'=> 'required',
            ]);

        DB::transaction(function () use ($request, $variant) {
            $this->service
                ->setModel($variant)
                ->setAttrs($request->all())
                ->updateVariantInfo(
                    $request->only(
                        'product_id', 'name', 'selling_price', 'upc', 'stock_reminder_quantity',
                        'warranty_duration_type', 'description', 'status_id', 'created_by'
                    )
                )
                ->updateVariantImages();
        });

        return updated_responses('variant');
    }

    private function countProductsVariants($product_id): int
    {
        return $this->service->query()
            ->where('product_id', $product_id)
            ->count();
    }

    public function destroy(Variant $variant)
    {
        $variants = $this->countProductsVariants($variant->product_id);
        throw_if($variants === 1, new GeneralException(__t('at_least_one_variant_should_available_for_each_product')));

        DB::transaction(function () use ($variant) {
            $this->service
                ->setModel($variant)
                ->checkVariantIsUsed()
                ->deleteVariant();
        });

        return deleted_responses('variant');
    }

    public function changeVariantStatus(Request $request, Variant $variant)
    {
        $this->service
            ->setModel($variant)
            ->setAttrs($request->only('status'))
            ->changeStatus();

        return updated_responses('status');
    }


    public function makeDefault(Request $request, Variant $variant)
    {
        foreach ($variant->attachments as $photo) {
            $photo->update([
                'fileable_id' => $request->product_id,
                'type' => $request->file_id === $photo->id ? 'variant_default_image' : 'variant'
            ]);
        }

        return updated_responses('variant_default_success');
    }

    public function getVariantProductTax(BranchOrWarehouse $branch, Variant $variant)
    {
        try {
            $defaultTax = $variant->tax->is_default ?? false;
            if ($defaultTax) {
                $taxData = $variant->stock->branchOrWarehouse->tax;
            } else {
                $taxData = $variant->tax;
            }

            $getFlatDiscount = Discount::query()->where([
                'discount_type' => 'flat_discount',
                'branch_or_warehouse_id' => $branch->id,
                'status_id' => resolve(StatusRepository::class)->discountActive()
            ])->first();

            if ($getFlatDiscount) {
                $discountInfo = null;
            } else {
                $discountInfo = DiscountProduct::query()
                    ->whereHas('discount', function ($query) {
                        $query->where('status_id', resolve(StatusRepository::class)->discountActive());
                    })->where([
                        'variant_id' => $variant->id,
                        'branch_or_warehouse_id' => $branch->id
                    ])->orderByDesc('id')->first();
            }


            return [
                'tax' => $taxData ?? null,
                'discount' => $discountInfo->discount ?? null
            ];
        } catch (\Exception $exception) {
            return [
                'tax' => null,
                'discount' => null,
                'error' => $exception->getMessage()
            ];
        }
    }

}
