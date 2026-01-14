<?php

namespace App\Models\Pos\Product\Tax;

use App\Models\Pos\Inventory\Stock\Stock;
use App\Models\Tenant\Branch\Relationship\BranchRelationship;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\Rules\TaxRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends TenantModel
{

    use HasFactory,
        TaxRules,
        BranchRelationship;

    protected $fillable = ['name', 'type', 'percentage', 'is_default', 'tenant_id'];

    protected $casts = [
        'percentage' => 'float'
    ];

    //group tax
    public function taxTaxes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaxTax::class);
    }

    public function stocks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
