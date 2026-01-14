<?php

namespace App\Models\Pos\Product\Product;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Traits\Relationship\StatusRelationship;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Traits\ProductRelationship;
use App\Models\Tenant\Traits\VariantRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Variant extends TenantModel
{
    use HasFactory,
        BootTrait,
        CreatedByRelationship,
        ProductRelationship,
        VariantRelationship,
        StatusRelationship;

    protected $fillable = [
        'product_id', 'upc', 'selling_price', 'status_id', 'tenant_id', 'name',
        'description', 'tax_id', 'stock_reminder_quantity'
    ];

    protected $hidden = [
        'tenant_id', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'product_id' => 'integer',
        'status_id' => 'integer',
        'tax_id' => 'integer',
        'stock_reminder_quantity' => 'integer',
    ];

    public function getWarrantyAttribute()
    {
        return $this->warranty_duration_type ?
            $this->warranty_duration . ' ' . __t($this->warranty_duration_type)
            : $this->warranty_duration;
    }
}
