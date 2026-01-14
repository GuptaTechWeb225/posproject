<?php

namespace App\Models\Tenant\Order;

use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Traits\ReturnOrderProductRelationship;

class ReturnOrderProduct extends TenantModel
{

    use ReturnOrderProductRelationship;

    protected $fillable = [
        'order_id',
        'return_order_id',
        'stock_id',
        'variant_id',
        'order_product_id',
        'price',
        'quantity',
        'tax_amount',
        'discount',
        'sub_total',
        'note',
        'tenant_id'
    ];
}
