<?php

namespace App\Models\Tenant\Traits;

use App\Models\Core\Auth\User;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\ReturnOrderProduct;
use App\Models\Tenant\Transaction\Transaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait ReturnOrderRelationship
{

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function branchOrWarehouse(): BelongsTo
    {
        return $this->belongsTo(BranchOrWarehouse::class, 'branch_or_warehouse_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function returnOrderProducts(): HasMany
    {
        return $this->hasMany(ReturnOrderProduct::class, 'return_order_id');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}