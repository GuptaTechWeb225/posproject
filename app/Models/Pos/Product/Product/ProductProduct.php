<?php

namespace App\Models\Pos\Product\Product;

use App\Models\Pos\Inventory\Stock\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductProduct extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'stock_id', 'variant_id', 'quantity'];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'parent_id', 'id');
    }

    public function variant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function stock(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    protected $table = 'product_product';

}
