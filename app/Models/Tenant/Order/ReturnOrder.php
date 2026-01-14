<?php

namespace App\Models\Tenant\Order;

use App\Models\Pos\Traits\Scope\BranchOrWarehouseScope;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Traits\ReturnOrderRelationship;

class ReturnOrder extends TenantModel
{

    use ReturnOrderRelationship, BranchOrWarehouseScope;

    protected $fillable = [
        'order_id',
        'branch_or_warehouse_id',
        'customer_id',
        'invoice_number',
        'last_invoice_number',
        'order_invoice_number',
        'reference_number',
        'type',
        'sub_total',
        'total_tax',
        'discount',
        'change_return',
        'note',
        'returned_at',
        'created_by',
        'tenant_id'
    ];


    protected $casts = [
        'branch_or_warehouse_id' => 'integer',
        'created_by' => 'integer',
        'tenant_id' => 'integer',
        'returned_at' => 'date:d-m-Y',
    ];

}
