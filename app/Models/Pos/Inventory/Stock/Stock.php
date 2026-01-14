<?php

namespace App\Models\Pos\Inventory\Stock;


use App\Models\Pos\Traits\Scope\BranchOrWarehouseScope;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Traits\StockRelationship;

class Stock extends TenantModel
{
    use StockRelationship, BranchOrWarehouseScope;

    protected $fillable = [
        'branch_or_warehouse_id',
        'variant_id',
        'avg_purchase_price',
        'total_purchase_qty',
        'total_sales_qty',
        'total_adjustment_qty',
        'total_internal_transfer_sent_qty',
        'total_internal_transfer_received_qty',
        'available_qty'
    ];

    protected $casts = [
        'variant_id' => 'integer',
        'total_purchase_qty' => 'integer',
        'total_sales_qty' => 'integer',
        'total_adjustment_qty' => 'integer',
        'total_internal_transfer_sent_qty' => 'integer',
        'total_internal_transfer_received_qty' => 'integer',
        'available_qty' => 'integer',
    ];

}
