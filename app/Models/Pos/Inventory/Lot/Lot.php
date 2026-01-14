<?php

namespace App\Models\Pos\Inventory\Lot;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Inventory\Rules\LotRules;
use App\Models\Pos\Traits\Relationship\StatusRelationship;
use App\Models\Pos\Traits\Scope\BranchOrWarehouseScope;
use App\Models\Tenant\Traits\LotRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lot extends BaseModel
{
    use HasFactory, BootTrait, CreatedByRelationship, LotRules, StatusRelationship,
        LotRelationship, BranchOrWarehouseScope;

    protected $fillable = [
        'branch_or_warehouse_id',
        'supplier_id',
        'purchase_date',
        'status_id',
        'other_charge',
        'discount_amount',
        'created_by',
        'reference_no',
        'adjusted_with_stock',
        'note',
        'total_amount'
    ];

    protected $casts = [
        'branch_or_warehouse_id' => 'integer',
        'adjusted_with_stock'=>'boolean',
        'supplier_id' => 'integer',
        'other_charge' => 'integer',
        'reference_no' => 'string',
        'created_by' => 'integer',
        'created_at'  => 'date:d-m-Y',
        'total_amount' => 'integer',
    ];
}
