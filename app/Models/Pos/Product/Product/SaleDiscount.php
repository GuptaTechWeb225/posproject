<?php

namespace App\Models\Pos\Product\Product;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDiscount extends TenantModel
{
    use HasFactory, BootTrait, CreatedByRelationship;

    protected $fillable = [
        'discountable_type','discountable_id','amount','created_by','tenant_id'
    ];
}
