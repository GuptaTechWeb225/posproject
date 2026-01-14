<?php


namespace App\Models\Tenant\Discount\Relationship;


use App\Models\Tenant\Discount\Coupon;
use App\Models\Tenant\Discount\DiscountProduct;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait DiscountRelationship
{

    public function coupons(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Coupon::class,
            'coupon_discount',
            'discount_id',
            'coupon_id'
        );
    }

    public function discountProducts(): HasMany
    {
        return $this->hasMany(DiscountProduct::class, 'discount_id');
    }
}