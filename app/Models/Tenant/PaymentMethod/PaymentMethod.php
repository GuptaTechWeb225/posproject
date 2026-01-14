<?php

namespace App\Models\Tenant\PaymentMethod;

use App\Models\Core\Traits\BootTrait;
use App\Models\Tenant\PaymentMethod\Relationship\PaymentMethodRelationship;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Rules\PaymentMethodRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends TenantModel
{
    use HasFactory,
        PaymentMethodRules,
        PaymentMethodRelationship,
        BootTrait;

    protected $fillable = [
        'name',
        'alias',
        'status_id',
        'is_default',
        'is_for_client',
        'created_by',
        'tenant_id',
        'rounded_to'
    ];

}
