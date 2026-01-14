<?php

namespace App\Models\Tenant\Customer\Relationship;

use App\Models\Tenant\Customer\Customer;

trait CustomerGroupRelationship
{
    public function customers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Customer::class);
    }
}