<?php

namespace App\Models\Pos\Product\Category;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Product\SubCategory\SubCategory;
use App\Models\Pos\Traits\Relationship\StatusRelationship;
use App\Models\Tenant\Rules\CategoryRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends TenantModel
{
    use HasFactory,
        CreatedByRelationship,
        BootTrait,
        StatusRelationship,
        CategoryRules;

    protected $fillable = [
        'name', 'description', 'created_by', 'tenant_id', 'status_id'
    ];

    protected $casts = [
        'created_by' => 'integer',
        'tenant_id' => 'integer',
        'status_id' => 'integer',
    ];

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
