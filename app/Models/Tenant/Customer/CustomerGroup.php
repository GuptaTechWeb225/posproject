<?php

namespace App\Models\Tenant\Customer;

use App\Models\Tenant\Customer\Relationship\CustomerGroupRelationship;
use App\Models\Tenant\Rules\CustomerGroupRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerGroup extends TenantModel
{
    use HasFactory, CustomerGroupRelationship, CustomerGroupRules;

    protected $fillable = ['name', 'is_default', 'discount_id', 'tenant_id'];
}
