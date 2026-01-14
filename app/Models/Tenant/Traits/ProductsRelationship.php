<?php


namespace App\Models\Tenant\Traits;

use App\Models\Pos\Product\Product\Product;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait ProductsRelationship
{
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}