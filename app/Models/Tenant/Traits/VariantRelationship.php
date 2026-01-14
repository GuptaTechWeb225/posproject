<?php

namespace App\Models\Tenant\Traits;

use App\Models\Core\File\File;
use App\Models\Pos\Inventory\AdjustmentVariant;
use App\Models\Pos\Inventory\LotVariant\LotVariant;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Pos\Product\Attribute\AttributeVariation;
use App\Models\Pos\Product\Product\ProductProduct;
use App\Models\Tenant\Discount\DiscountProduct;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait VariantRelationship
{
    protected $branch_or_warehouse_id = 1;

    //only for selected branch or warehouse
    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    //for all branch or warehouse
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function discountProduct(): BelongsTo
    {
        return $this->belongsTo(DiscountProduct::class, 'id', 'variant_id')
            ->orderBy('id', 'DESC');
    }

    public function activeDiscountProduct(): BelongsTo
    {
        return $this->belongsTo(DiscountProduct::class, 'id', 'variant_id')
                ->whereHas('discount', function (Builder $builder) {
                $builder->where('status_id', resolve(StatusRepository::class)->discountActive());
        });
    }

    public function attributesVariations(): BelongsToMany
    {
        return $this->belongsToMany(AttributeVariation::class, 'attribute_variation_variant');
    }

    public function productProducts(): HasMany
    {
        return $this->hasMany(ProductProduct::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable')->where('type', 'variant_product');
    }

    public function thumbnail(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')->where('type', 'variant_thumbnail');
    }

    public function scopeActive($query)
    {
        return $query->where('status_id', resolve(StatusRepository::class)->productActive());
    }

    //stock adjustment for this variant
    public function adjustmentVariant(): HasOne
    {
        return $this->hasOne(AdjustmentVariant::class);
    }

    public function lotVariant(): HasOne
    {
        return $this->hasOne(LotVariant::class);
    }
}