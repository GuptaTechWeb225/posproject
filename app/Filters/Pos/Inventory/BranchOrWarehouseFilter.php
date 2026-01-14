<?php


namespace App\Filters\Pos\Inventory;


use App\Filters\Core\traits\NameFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\Core\traits\StatusIdFilter;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Traits\SearchByNameFilterTrait;

class BranchOrWarehouseFilter extends FilterBuilder
{
    use  NameFilter, SearchFilterTrait, StatusIdFilter, DateRangeFilter, SearchByNameFilterTrait;
}
