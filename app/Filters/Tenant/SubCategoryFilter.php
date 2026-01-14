<?php


namespace App\Filters\Tenant;


use App\Filters\Core\BaseFilter;
use App\Filters\Core\traits\CreatedByFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Traits\SearchByNameFilterTrait;

class SubCategoryFilter extends BaseFilter
{
    use SearchFilterTrait, DateRangeFilter, CreatedByFilter, SearchByNameFilterTrait;
}