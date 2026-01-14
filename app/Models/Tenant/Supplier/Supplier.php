<?php

namespace App\Models\Tenant\Supplier;

use App\Models\Core\Auth\Traits\Relationship\UserRelationship;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Tenant\Rules\SupplierRules;
use App\Models\Tenant\Supplier\Traits\SupplierRelationship;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Traits\ContactRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends TenantModel
{
    use HasFactory,
        CreatedByRelationship,
        ContactRelationship,
        SupplierRelationship,
        UserRelationship,
        SupplierRules,
        BootTrait;

    protected $fillable = [
        'name', 'supplier_no', 'created_by', 'status_id'
    ];

}
