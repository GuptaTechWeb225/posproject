<?php

namespace App\Models\Pos\Product\Brand;

use App\Models\Core\File\File;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Traits\Relationship\StatusRelationship;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends TenantModel
{
    use HasFactory,
        CreatedByRelationship,
        BootTrait,
        StatusRelationship,
        BrandRules;

    protected $fillable = [
        'name', 'description', 'created_by', 'tenant_id', 'description'
    ];

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function brandLogo(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('type', 'brand_logo');
    }
}
