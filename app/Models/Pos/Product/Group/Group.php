<?php

namespace App\Models\Pos\Product\Group;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Product\Product\Product;
use App\Models\Pos\Traits\Relationship\StatusRelationship;
use App\Models\Tenant\Rules\GroupRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends TenantModel
{
    use HasFactory, CreatedByRelationship, BootTrait, StatusRelationship, GroupRules;

    protected $fillable = [
        'name', 'description', 'created_by', 'tenant_id', 'status_id'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

}
