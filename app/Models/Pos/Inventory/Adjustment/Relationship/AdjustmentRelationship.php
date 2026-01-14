<?php

namespace App\Models\Pos\Inventory\Adjustment\Relationship;

use App\Models\Pos\Inventory\AdjustmentVariant;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait AdjustmentRelationship
{
    public function branchOrWarehouse(): BelongsTo
    {
        return $this->belongsTo(BranchOrWarehouse::class, 'branch_or_warehouse_id');
    }

    public function adjustmentVariants(): HasMany
    {
        return $this->hasMany(AdjustmentVariant::class, 'adjustment_id');
    }
}