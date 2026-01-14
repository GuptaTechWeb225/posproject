<?php

namespace App\Models\Tenant\Expense;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Tenant\Rules\ExpenseAreaRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseArea extends TenantModel
{
    use HasFactory, BootTrait, CreatedByRelationship, ExpenseAreaRules;

    protected $fillable = [
        'name', 'description', 'is_add_to_report', 'tenant_id', 'created_by'
    ];

    public function expenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
