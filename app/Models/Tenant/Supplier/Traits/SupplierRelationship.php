<?php

namespace App\Models\Tenant\Supplier\Traits;

use App\Models\Pos\Inventory\Lot\Lot;
use App\Models\Tenant\Customer\Address;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait SupplierRelationship
{

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class)->orderByDesc('id');
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class, 'supplier_id');
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class, 'supplier_id');
    }

}