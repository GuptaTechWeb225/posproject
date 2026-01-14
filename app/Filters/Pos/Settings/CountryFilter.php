<?php

namespace App\Filters\Pos\Settings;

use App\Filters\Core\traits\NameFilter;
use App\Filters\Core\traits\StatusIdFilter;
use App\Filters\FilterBuilder;

class CountryFilter extends FilterBuilder
{
    use NameFilter, StatusIdFilter;

}