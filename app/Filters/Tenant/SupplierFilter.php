<?php


namespace App\Filters\Tenant;


use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\Core\traits\StatusIdFilter;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Traits\SearchByNameFilterTrait;

class SupplierFilter extends FilterBuilder
{
    use DateRangeFilter, SearchFilterTrait, SearchByNameFilterTrait, StatusIdFilter;

    public function supplier($id = null)
    {
        $this->builder
            ->where('id', 'LIKE', "%{$id}%");
    }
}