<?php

namespace App\Models\Pos\Product\Product\Relationship;

use App\Models\Core\File\File;
use App\Models\Core\Status;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Pos\Product\Brand\Brand;
use App\Models\Pos\Product\Category\Category;
use App\Models\Pos\Product\Duration\Duration;
use App\Models\Pos\Product\Group\Group;
use App\Models\Pos\Product\Product\ProductProduct;
use App\Models\Pos\Product\Product\Variant;
use App\Models\Pos\Product\SubCategory\SubCategory;
use App\Models\Pos\Product\Unit\Unit;
use App\Models\Tenant\Order\OrderProduct;
use App\Models\Tenant\Traits\TagsRelationship;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait ProductAllRelationship
{
    use BootTrait, CreatedByRelationship, TagsRelationship;

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class)->orderByDesc('id');
    }

    public function productDetails(): HasOne
    {
        return $this->hasOne(Variant::class);
    }

    public function variant(): HasOne
    {
        return $this->hasOne(Variant::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    //combo products
    public function productProducts(): HasMany
    {
        return $this->hasMany(ProductProduct::class, 'parent_id','id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable')->where('type','product');
    }

    //for product thumbnail
    public function thumbnail(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')->where('type','product_thumbnail');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function duration(): BelongsTo
    {
        return $this->belongsTo(Duration::class);
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class,'order_product_id');
    }

}