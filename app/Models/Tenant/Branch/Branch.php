<?php

namespace App\Models\Tenant\Branch;

use App\Models\Core\Auth\User;
use App\Models\Core\Traits\StatusRelationship;
use App\Models\Pos\Product\Tax\Relationship\TaxRelationship;
use App\Models\Tenant\Branch\Relationship\ManagerRelationship;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Rules\BranchRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends TenantModel
{
    use HasFactory,
        ManagerRelationship,
        StatusRelationship,
        BranchRules,
        TaxRelationship;


    protected $fillable = [
        'name', 'manager_id', 'location', 'status_id', 'tenant_id'
    ];

    public function isInActive(): bool
    {
        return optional($this->status)->name === 'status_inactive';
    }

    public function isActive(): bool
    {
        return optional($this->status)->name === 'status_active';
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
