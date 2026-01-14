<?php


namespace App\Filters\Tenant;


use App\Filters\Core\traits\NameFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;

class TaxFilter extends FilterBuilder
{
    use  NameFilter, SearchFilterTrait, DateRangeFilter;

    public function type ($type= null)
    {
        $this->whereClause('type', $type);
    }

}
