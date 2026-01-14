<?php

namespace App\Filters\Tenant;

use App\Filters\Core\traits\NameFilter;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\FilterBuilder;
use App\Filters\Traits\SearchByNameFilterTrait;


class CustomerGroupFilter extends FilterBuilder
{

    use SearchFilterTrait,DateRangeFilter, NameFilter, SearchByNameFilterTrait;

}