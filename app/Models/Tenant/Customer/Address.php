<?php

namespace App\Models\Tenant\Customer;

use App\Models\Pos\Settings\Country;
use App\Models\Tenant\Rules\CustomerAddressRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends TenantModel
{
    use HasFactory, CustomerAddressRules;

    protected $fillable = [
        'customer_id', 'supplier_id', 'name', 'country_id', 'city', 'zip_code', 'area', 'details'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'supplier_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
