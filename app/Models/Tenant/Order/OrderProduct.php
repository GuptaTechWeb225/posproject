<?php

namespace App\Models\Tenant\Order;

use App\Models\Tenant\Traits\OrderProductRelationship;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use OrderProductRelationship;

    protected $fillable = [
        'branch_or_warehouse_id', 'order_id', 'stock_id', 'variant_id', 'order_product_id', 'ordered_at', 'purchase_price', 'price', 'selling_price', 'avg_purchase_price',
        'quantity', 'tax_amount', 'discount_type', 'discount_value', 'discount_amount', 'sub_total', 'note', 'tenant_id'
    ];
}
