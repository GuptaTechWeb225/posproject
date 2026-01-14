<?php

namespace App\Models\Tenant\Sales\Cash;

use App\Models\Core\Auth\User;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\StatusRelationship;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Pos\Inventory\Rules\CashRegisterRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegister extends TenantModel
{
    use StatusRelationship, BootTrait, CashRegisterRules;

    protected $fillable = [
        'name',
        'branch_or_warehouse_id',
        'opened_by',
        'closed_by',
        'created_by',
        'updated_by',
        'opening_time',
        'closing_time',
        'opening_balance',
        'closing_balance',
        'multiple_access',
        'status_id'
    ];

    public function cashRegisterLogs(): HasMany
    {
        return $this->hasMany(CashRegisterLog::class, 'cash_register_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function openedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function branchOrWarehouse(): BelongsTo
    {
        return $this->belongsTo(BranchOrWarehouse::class, 'branch_or_warehouse_id');
    }

}
