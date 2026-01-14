<?php

namespace App\Models\Tenant\Discount;

use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Product\Product\Variant;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountProduct extends Model
{
    protected $fillable = [
        'discount_id',
        'branch_or_warehouse_id',
        'variant_id',
        'product_id'
    ];

    use HasFactory;

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function activeDiscount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id')->where('status_id', resolve(StatusRepository::class)->discountActive());
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
