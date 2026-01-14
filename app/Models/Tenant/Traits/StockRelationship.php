<?php

namespace App\Models\Tenant\Traits;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Pos\Product\Tax\Tax;
use App\Models\Tenant\Order\OrderProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait StockRelationship
{
    use HasFactory, BootTrait, CreatedByRelationship;

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function branchOrWarehouse(): BelongsTo
    {
        return $this->belongsTo(BranchOrWarehouse::class, 'branch_or_warehouse_id');
    }

}