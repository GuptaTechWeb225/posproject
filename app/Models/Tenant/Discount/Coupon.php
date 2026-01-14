<?php

namespace App\Models\Tenant\Discount;

use App\Models\Tenant\Rules\CouponRules;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Traits\CommentRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends TenantModel
{
    use HasFactory, CommentRelationship, CouponRules;

    protected $fillable = [
        'name', 'start_at', 'end_at', 'code', 'discount'
    ];
}
