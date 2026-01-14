<?php

namespace App\Models\Tenant\Customer;

use App\Models\Core\Auth\Traits\Relationship\UserRelationship;
use App\Models\Core\Traits\BootTrait;
use App\Models\Tenant\Customer\Relationship\CustomerRelationship;
use App\Models\Tenant\Rules\CustomerRules;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Traits\ContactRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends TenantModel
{
    use HasFactory, CustomerRules, CustomerRelationship, ContactRelationship, BootTrait, UserRelationship;

    protected $fillable = [
        'customer_group_id', 'first_name', 'last_name','email','phone_number', 'tin', 'created_by', 'tenant_id'
    ]; //this phone_number is not used. phone_number is using from contacts table ...

    public function getFullNameAttribute()
    {
        return $this->last_name
            ? $this->first_name.' '.$this->last_name
            : $this->first_name;
    }

    public function getNameAttribute()
    {
        return $this->full_name;
    }

    protected $appends = ['full_name'];

}
