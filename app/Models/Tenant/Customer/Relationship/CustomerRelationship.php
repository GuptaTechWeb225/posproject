<?php

namespace App\Models\Tenant\Customer\Relationship;

use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Tenant\Customer\Address;
use App\Models\Tenant\Customer\CustomerGroup;
use App\Models\Tenant\Order\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CustomerRelationship
{
    use CreatedByRelationship;

    public function customerGroup(): BelongsTo
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class)->orderBy('id', 'desc');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}