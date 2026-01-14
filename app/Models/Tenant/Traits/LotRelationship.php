<?php


namespace App\Models\Tenant\Traits;

use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Pos\Inventory\LotVariant\LotVariant;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Tenant\Supplier\Supplier;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait LotRelationship
{
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function branchOrWarehouse(): BelongsTo
    {
        return $this->belongsTo(BranchOrWarehouse::class);
    }

    public function lotVariants(): HasMany
    {
        return $this->hasMany(LotVariant::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}