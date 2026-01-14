<?php

namespace App\Models\Pos\Inventory;

use App\Models\Core\Traits\StatusRelationship;
use App\Models\Pos\Inventory\Relationship\BranchOrWarehouseRelationship;
use App\Models\Pos\Traits\Scope\PermissionScope;
use App\Models\Tenant\Branch\Relationship\ManagerRelationship;
use App\Models\Tenant\Rules\BranchOrWarehouseRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchOrWarehouse extends TenantModel
{
    use HasFactory,
        ManagerRelationship,
        StatusRelationship,
        BranchOrWarehouseRules,
        BranchOrWarehouseRelationship,
        PermissionScope;


    protected $fillable = [
        'name','phone','email', 'created_by', 'location', 'status_id', 'tenant_id','manager_id','type', 'tax_id'
    ];



}
