<?php

namespace App\Models\Pos\Inventory\Relationship;

use App\Models\Core\Auth\User;
use App\Models\Pos\Inventory\Adjustment\Adjustment;
use App\Models\Pos\Inventory\InternalTransfer;
use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Pos\Product\Tax\Tax;
use App\Models\Tenant\Discount\Discount;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\ReturnOrder;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait BranchOrWarehouseRelationship
{
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    //for pos view product
    public function flat_discount()
    {
        return $this->hasOne(Discount::class)
            ->where([
                'discount_type' => 'flat_discount',
                'status_id' => resolve(StatusRepository::class)->discountActive()
            ]);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'branch_or_warehouse_id');
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class, 'branch_or_warehouse_id');
    }

    public function returnOrders(): HasMany
    {
        return $this->hasMany(ReturnOrder::class, 'branch_or_warehouse_id');
    }

    public function internalTransfers(): HasMany
    {
        return $this->hasMany(InternalTransfer::class, 'branch_or_warehouse_from_id');
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(Adjustment::class, 'branch_or_warehouse_id');
    }

    public static function checkBranchOrWarehouseIsActive($branch_or_warehouse_id = null): bool
    {
        $branch_or_warehouse =  self::query()->where('id', $branch_or_warehouse_id)->with('status:id,name')->first();
        return $branch_or_warehouse->status->name === 'status_active';
    }

}