<?php


namespace App\Filters\Tenant;


use App\Filters\Core\traits\NameFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\Core\traits\StatusIdFilter;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;

class BranchFilter extends FilterBuilder
{
    use  NameFilter, SearchFilterTrait, StatusIdFilter, DateRangeFilter;

}
