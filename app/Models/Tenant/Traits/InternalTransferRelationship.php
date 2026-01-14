<?php

namespace App\Models\Tenant\Traits;

use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Pos\Inventory\InternalTransferVariant;
use App\Models\Pos\Inventory\Stock\Stock;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait InternalTransferRelationship
{
    public function branchOrWarehouseFrom(): BelongsTo
    {
        return $this->belongsTo(BranchOrWarehouse::class, 'branch_or_warehouse_from_id');
    }

    public function branchOrWarehouseTo(): BelongsTo
    {
        return $this->belongsTo(BranchOrWarehouse::class, 'branch_or_warehouse_to_id');
    }

    public function internalTransferVariants(): HasMany
    {
        return $this->hasMany(InternalTransferVariant::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}