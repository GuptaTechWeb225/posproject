<?php


namespace App\Filters\Pos\Inventory;

use App\Filters\Pos\Traits\BranchOrWarehouseFilter;
use App\Filters\Core\traits\StatusIdFilter;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use Illuminate\Database\Eloquent\Builder;

class StockFilter extends FilterBuilder
{
    use DateRangeFilter, StatusIdFilter, BranchOrWarehouseFilter;

    public function variantName($variant_name = null)
    {
        $this->builder->when($variant_name, function (Builder $builder) use ($variant_name) {
            $builder->whereHas('variant', function (Builder $q) use ($variant_name){
                $q->where('name', 'LIKE', "%{$variant_name}%");
            });
        });
    }

    public function search($variant_name = null)
    {
        $this->builder->when($variant_name, function (Builder $builder) use ($variant_name) {
            $builder->whereHas('variant', function (Builder $q) use ($variant_name){
                    $q->where('name', 'LIKE', "%{$variant_name}%");
                    $q->orWhere('upc', 'LIKE', "%{$variant_name}%");
            });
        });
    }

    public function productType($product_type = null)
    {
        $this->builder->when($product_type, function (Builder $builder) use ($product_type) {
            $builder->whereHas('variant', function (Builder $q) use ($product_type){
                $q->whereHas('product', function (Builder $q) use ($product_type){
                    $q->where('product_type', '=', $product_type);
                });
            });
        });
    }

    public function group($group_id = null)
    {
        $this->builder->when($group_id, function (Builder $builder) use ($group_id) {
            $builder->whereHas('variant', function (Builder $q) use ($group_id){
                $q->whereHas('product', function (Builder $q) use ($group_id){
                    $q->where('group_id', '=', $group_id);
                });
            });
        });
    }

    public function category($category_id = null)
    {
        $this->builder->when($category_id, function (Builder $builder) use ($category_id) {
            $builder->whereHas('variant', function (Builder $q) use ($category_id){
                $q->whereHas('product', function (Builder $q) use ($category_id){
                    $q->where('category_id', '=', $category_id);
                });
            });
        });
    }

    public function brand($brand_id = null)
    {
        $this->builder->when($brand_id, function (Builder $builder) use ($brand_id) {
            $builder->whereHas('variant', function (Builder $q) use ($brand_id){
                $q->whereHas('product', function (Builder $q) use ($brand_id){
                    $q->where('brand_id', '=', $brand_id);
                });
            });
        });
    }

    public function unit($unit_id = null)
    {
        $this->builder->when($unit_id, function (Builder $builder) use ($unit_id) {
            $builder->whereHas('variant', function (Builder $q) use ($unit_id){
                $q->whereHas('product', function (Builder $q) use ($unit_id){
                    $q->where('unit_id', '=', $unit_id);
                });
            });
        });
    }

}