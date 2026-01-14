<?php


namespace App\Filters\Tenant;


use App\Filters\Core\traits\CreatedByFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\Core\traits\StatusIdFilter;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Traits\SearchByNameFilterTrait;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter extends FilterBuilder
{
    use SearchFilterTrait, DateRangeFilter, CreatedByFilter, StatusIdFilter, SearchByNameFilterTrait;

    public function search($value = null)
    {
        $this->builder
            ->where('name', 'LIKE', "%{$value}%");
    }

    public function subCategories($sub_category_id = null)
    {
        return $this->builder->when($sub_category_id, function (Builder $builder) use ($sub_category_id) {
            $builder->whereHas('subCategories', function (Builder $builder) use ($sub_category_id) {
                return $builder->where('id', $sub_category_id);
            });
        });
    }
}