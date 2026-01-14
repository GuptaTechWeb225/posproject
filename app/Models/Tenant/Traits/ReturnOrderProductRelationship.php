<?php

namespace App\Models\Tenant\Traits;

use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Tenant\Order\ReturnOrder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ReturnOrderProductRelationship
{
    public function returnOrder(): BelongsTo
    {
        return $this->belongsTo(ReturnOrder::class);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'order_product_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }

}