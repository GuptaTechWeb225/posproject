<?php

namespace App\Models\Core;

use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;

class BaseModel extends Model
{
    use LogsActivity;

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
    public function createdRules()
    {
        return [
            
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }

    public function scopeFilters($query, FilterBuilder $filter): Builder
    {
        return $filter->apply($query);
    }

}
