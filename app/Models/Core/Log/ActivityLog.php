<?php


namespace App\Models\Core\Log;


use App\Filters\FilterBuilder;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;

class ActivityLog extends Activity
{
    public function scopeFilters($query, FilterBuilder $filter): Builder
    {
        return $filter->apply($query);
    }
}
