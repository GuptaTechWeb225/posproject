<?php


namespace App\Filters\Tenant;


use App\Filters\Core\traits\CreatedByFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\Core\traits\StatusIdFilter;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Traits\SearchByNameFilterTrait;
use Illuminate\Database\Eloquent\Builder;

class VariantFilter extends FilterBuilder
{
    use SearchFilterTrait, DateRangeFilter, CreatedByFilter, StatusIdFilter, SearchByNameFilterTrait;

    public function search($value = null)
    {
        $this->builder->where('name', 'LIKE', "%{$value}%")
            ->orWhere(function (Builder $builder) use ($value) {
                $builder->where('upc', $value);
            });
    }
}