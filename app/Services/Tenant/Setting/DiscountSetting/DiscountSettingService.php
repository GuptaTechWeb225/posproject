<?php

namespace App\Services\Tenant\Setting\DiscountSetting;

use App\Filters\Tenant\VariantFilter;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Tenant\Discount\Discount;
use App\Models\Tenant\Discount\DiscountProduct;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Core\BaseService;

class DiscountSettingService extends BaseService
{
    public Variant $variantModel;
    public VariantFilter $variantFilter;
    public Stock $stockModel;

    public function __construct(Discount $discount, Variant $variantModel, VariantFilter $variantFilter, Stock $stock)
    {
        $this->model = $discount;
        $this->variantModel = $variantModel;
        $this->variantFilter = $variantFilter;
        $this->stockModel = $stock;
    }

    public function storeDiscount()
    {
        $discountData = array_merge(
            $this->getAttributes('branch_or_warehouse_id', 'name', 'discount_type', 'type', 'value', 'start_at', 'end_at', 'note'),
            [
                'status_id' => resolve(StatusRepository::class)->discountInactive(),
            ]
        );
        $this->model->fill($discountData)->save();
        return $this;
    }

    public function updateDiscount()
    {
        $discountData = array_merge(
            $this->getAttributes('branch_or_warehouse_id', 'name', 'discount_type', 'type', 'value', 'start_at', 'end_at', 'note'),
            [
                'status_id' => resolve(StatusRepository::class)->discountInactive(),
            ]
        );
        $this->getModel()->update($discountData);

        if ($this->model->discount_type === 'flat_discount') {
            $this->model->discountProducts()->delete();
        }

        return $this;
    }

    public function storeDiscountProduct()
    {
        $discountProducts = [];

        if (request()->discount_products) {
            foreach (request()->discount_products as $discountProduct) {
                $discountProducts[] = $this->discountProductRequest($discountProduct);
            }
        }
        $this->model
            ->discountProducts()
            ->insert($discountProducts);

        return $this;
    }

    public function updateDiscountProduct()
    {
        $this->model->discountProducts()->delete();

        $discountProducts = [];

        if (request()->discount_products) {
            foreach (request()->discount_products as $discountProduct) {
                $discountProducts[] = $this->discountProductRequest($discountProduct);
            }
        }
        $this->model
            ->discountProducts()
            ->insert($discountProducts);

        return $this;
    }

    public function discountProductRequest($discountProduct): array
    {
        return [
            'discount_id' => $this->model->id,
            'branch_or_warehouse_id' => $discountProduct['branch_or_warehouse_id'],
            'product_id' => $discountProduct['product_id'],
            'variant_id' => $discountProduct['variant_id']
        ];
    }

    public function discountVariantProducts($branchId)
    {
        return $this->stockModel
            ->where('branch_or_warehouse_id', $branchId)
            ->select('id', 'variant_id', 'branch_or_warehouse_id', 'available_qty')
            ->with([
                'variant:id,name,product_id,upc',
                'variant.thumbnail',
                'variant.activeDiscountProduct.discount:id,name,branch_or_warehouse_id,discount_type,type,value,start_at,end_at,status_id',
            ])
            ->paginate(request()->get('per_page'));
    }

    public function removeExistingDiscountProduct()
    {
        if ($this->model->discountProducts()->count() > 0) {
            foreach ($this->model->discountProducts as $key => $item) {
                $discountProduct = DiscountProduct::query()->whereHas('activeDiscount')->orderByDesc('id')->where('discount_id', '!=', $this->model->id)->where([
                    'variant_id' => $item->variant_id,
                    'product_id' => $item->product_id,
                    'branch_or_warehouse_id' => $item->branch_or_warehouse_id
                ])->first();
                if ($discountProduct) {
                    if ($discountProduct->activeDiscount->start_at <= $this->model->start_at || $discountProduct->activeDiscount->end_at <= $this->model->end_at) {
                        $discountProduct->delete();
                    }
                }
            }
        }
        return $this;
    }

    public function changeStatus()
    {
        if ($this->model->discount_type === 'flat_discount') {
            if ($this->model->status_id == resolve(StatusRepository::class)->discountInactive()) {
                Discount::query()
                    ->where([
                        'branch_or_warehouse_id' => $this->model->branch_or_warehouse_id,
                        'discount_type' => 'flat_discount'
                    ])
                    ->update([
                        'status_id' => resolve(StatusRepository::class)->discountInactive()
                    ]);

                $discount = $this->model;
                $discount->status_id = resolve(StatusRepository::class)->discountActive();
            } else {
                $discount = $this->model;
                $discount->status_id = resolve(StatusRepository::class)->discountInactive();
            }
        } else {
            $discountStatus = $this->model->status_id == resolve(StatusRepository::class)->discountActive() ? resolve(StatusRepository::class)->discountInactive() : resolve(StatusRepository::class)->discountActive();
            if ($this->model->status->name == 'status_inactive' && $this->model->status->type === 'discount') {
                $this->removeExistingDiscountProduct();
            }
            $discount = $this->model;
            $discount->status_id = $discountStatus;
        }

        $discount->published_by = auth()->id();
        $discount->save();

        return $this;
    }


}