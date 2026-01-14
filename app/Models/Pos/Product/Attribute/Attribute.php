<?php

namespace App\Models\Pos\Product\Attribute;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Tenant\Rules\AttributeRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Attribute extends TenantModel
{
    use HasFactory, CreatedByRelationship, BootTrait, AttributeRules;

    protected $fillable = [
        'name', 'created_by', 'tenant_id'
    ];

    public function attributeVariations(): HasMany
    {
        return $this->hasMany(AttributeVariation::class);
    }
}
