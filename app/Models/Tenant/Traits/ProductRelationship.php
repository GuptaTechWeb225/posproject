<?php


namespace App\Models\Tenant\Traits;

use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Product\Tax\Tax;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ProductRelationship
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }
}