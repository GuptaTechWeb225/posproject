<?php


namespace App\Filters\Tenant;



use App\Filters\Core\traits\NameFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Traits\SearchByIdTrait;

class AttributeFilter extends FilterBuilder
{

    use NameFilter, SearchFilterTrait, SearchByIdTrait, DateRangeFilter;

}