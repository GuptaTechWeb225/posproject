<?php

namespace App\Models\Pos\Product\SubCategory;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Product\Category\Category;
use App\Models\Pos\Traits\Relationship\StatusRelationship;
use App\Models\Tenant\Rules\NameValidationRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCategory extends TenantModel
{
    use HasFactory,
        CreatedByRelationship,
        NameValidationRules,
        BootTrait,
        StatusRelationship;

    protected $fillable = [
        'name', 'description', 'created_by', 'category_id', 'tenant_id', 'status_id'
    ];

    protected $casts = [
        'created_by' => 'integer',
        'category_id' => 'integer',
        'tenant_id' => 'integer',
        'status_id' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
