<?php

namespace App\Models\Pos\Inventory;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Inventory\Rules\InternalTransferRules;
use App\Models\Pos\Traits\Relationship\StatusRelationship;
use App\Models\Tenant\Traits\InternalTransferRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InternalTransfer extends BaseModel
{
    use HasFactory, StatusRelationship, CreatedByRelationship, InternalTransferRelationship, InternalTransferRules;

    protected $fillable = [
        'status_id', 'branch_or_warehouse_from_id','branch_or_warehouse_to_id',
        'total_transfer_cost', 'date', 'note', 'created_by', 'adjusted_with_stock',
        'reference_no'
    ];

    protected $casts = [
        'variant_id' => 'integer',
        'status_id' => 'integer',
        'branch_or_warehouse_from_id' => 'integer',
        'branch_or_warehouse_to_id' => 'integer',
    ];

    //its different so shouldn't load trait
    public function scopeBranchOrWarehouse($query, $branch_or_warehouse_id = null)
    {
        $branch_or_warehouse_id != "null" ?
            $query->where('branch_or_warehouse_from_id', $branch_or_warehouse_id)->orWhere('branch_or_warehouse_to_id', $branch_or_warehouse_id) : '';
    }
}
