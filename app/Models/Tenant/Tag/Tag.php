<?php

namespace App\Models\Tenant\Tag;

use App\Models\Core\Traits\BootTrait;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Traits\ProductsRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends TenantModel
{
    use HasFactory, BootTrait, ProductsRelationship;

    protected $fillable = [
        'name', 'color', 'created_by', 'tenant_id', 'status_id'
    ];
}
