<?php

namespace App\Models\Tenant\Branch\Relationship;

use App\Models\Pos\Inventory\Adjustment\Adjustment;
use App\Models\Pos\Inventory\InternalTransfer;
use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\ReturnOrder;

trait TenantUserRelationShip
{
    public function orders()
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function lots()
    {
        return $this->hasMany(Lot::class, 'created_by');
    }

    public function returnOrders()
    {
        return $this->hasMany(ReturnOrder::class, 'created_by');
    }

    public function internalTransfers()
    {
        return $this->hasMany(InternalTransfer::class, 'created_by');
    }
    public function adjustments()
    {
        return $this->hasMany(Adjustment::class, 'created_by');
    }
}