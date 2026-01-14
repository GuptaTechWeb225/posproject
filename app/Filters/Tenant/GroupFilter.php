<?php


namespace App\Filters\Tenant;


use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Traits\SearchByNameFilterTrait;

class GroupFilter extends FilterBuilder
{
    use SearchFilterTrait, DateRangeFilter, SearchByNameFilterTrait;
}