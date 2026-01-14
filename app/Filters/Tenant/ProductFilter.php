<?php


namespace App\Filters\Tenant;


use App\Filters\Core\traits\CreatedByFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\Core\traits\StatusIdFilter;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends FilterBuilder
{
    use SearchFilterTrait, DateRangeFilter, CreatedByFilter, StatusIdFilter;

    public function search($value = null)
    {
        $this->builder->where(function (Builder $builder) use ($value) {
            $builder->where('name', 'LIKE', "%{$value}%");
        })->orWhere(function (Builder $builder) use ($value) {
            $builder->whereHas('variants', function (Builder $builder) use ($value) {
                $builder->where('upc', $value);
            });
        });
    }

    public function groups($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereIn('group_id', explode(',', $value));
        });
    }

    public function categories($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereIn('category_id', explode(',', $value));
        });
    }

    public function subCategories($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereIn('sub_category_id', explode(',', $value));
        });
    }

    public function brands($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereIn('brand_id', explode(',', $value));
        });
    }

    public function attributes($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereHas('variants', function (Builder $builder) use ($value) {
                $builder->whereHas('attributesVariations', function (Builder $builder) use ($value) {
                    $builder->whereIn('attribute_id', explode(',', $value));
                });
            });
        });
    }

    public function units($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereIn('unit_id', explode(',', $value));
        });
    }

    public function status($value = null)
    {
        $productStatus = resolve(StatusRepository::class)->product("status_active", "status_inactive");

        $this->builder->when($value && $value == "true" || $value == "", function (Builder $builder) use ($productStatus) {
            $builder->where('status_id', array_search('status_active', $productStatus));
        }, function (Builder $builder) use ($productStatus) {
            $builder->where('status_id', array_search('status_inactive', $productStatus));
        });
    }

    public function productType($value = null)
    {
        $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->where('product_type', $value);
        });
    }
}