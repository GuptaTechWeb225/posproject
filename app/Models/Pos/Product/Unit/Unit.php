<?php

namespace App\Models\Pos\Product\Unit;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Traits\Relationship\StatusRelationship;
use App\Models\Pos\Traits\Rules\UnitRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends TenantModel
{
    use HasFactory, CreatedByRelationship, BootTrait, StatusRelationship, UnitRules;

    protected $fillable = [
        'name', 'status_id', 'tenant_id', 'short_name','created_by'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
