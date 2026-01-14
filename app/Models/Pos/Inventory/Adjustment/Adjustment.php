<?php

namespace App\Models\Pos\Inventory\Adjustment;

use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Inventory\Adjustment\Relationship\AdjustmentRelationship;
use App\Models\Pos\Inventory\Rules\AdjustmentRules;
use App\Models\Pos\Traits\Scope\BranchOrWarehouseScope;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adjustment extends TenantModel
{
    use HasFactory, CreatedByRelationship, AdjustmentRelationship, AdjustmentRules, BranchOrWarehouseScope;

    protected $fillable = [
        'branch_or_warehouse_id', 'adjustment_date', 'created_by',
        'reference_no', 'note'
    ];

    protected $casts = [
        'branch_or_warehouse_id' => 'integer',
        'created_by' =>  'integer',
        'adjusted_with_stock' =>  'integer',
    ];
}
