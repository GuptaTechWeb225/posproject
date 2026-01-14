<?php

namespace App\Models\Tenant\Order;

use App\Models\Pos\Traits\Scope\BranchOrWarehouseScope;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Traits\OrderRelationship;

class Order extends TenantModel
{
    use OrderRelationship, BranchOrWarehouseScope;

    protected $dates = ['ordered_at'];

    protected $fillable = [
        'branch_or_warehouse_id',
        'cash_register_id',
        'customer_id',
        'tax_id',
        'payment_type',
        'invoice_number',
        'last_invoice_number',
        'total_tax',
        'discount_id',
        'discount_type',
        'discount_value',
        'discount',
        'due_amount',
        'paid_amount',
        'sub_total',
        'grand_total',
        'change_return',
        'note',
        'payment_note',
        'ordered_at',
        'status_id',
        'created_by',
        'tenant_id'
    ];


    protected $casts = [
        'branch_or_warehouse_id' => 'integer',
        'status_id' => 'integer',
        'created_by' => 'integer',
        'tenant_id' => 'integer',
    ];
}
