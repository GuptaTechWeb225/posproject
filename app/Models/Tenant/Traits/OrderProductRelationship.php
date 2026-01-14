<?php


namespace App\Models\Tenant\Traits;


use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\OrderProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait OrderProductRelationship
{
    use HasFactory, BootTrait, CreatedByRelationship;

    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function stock():BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function baseProduct():BelongsTo
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function returns():HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'order_product_id');
    }
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class,'variant_id');
    }

}