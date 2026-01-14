<?php

namespace App\Filters\Pos\Product\Duration;

use App\Filters\Core\traits\CreatedByFilter;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;

class DurationFilter extends FilterBuilder
{
    use DateRangeFilter, CreatedByFilter;

}
