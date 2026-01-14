<?php

namespace App\Models\Tenant\Expense;

use App\Filters\Traits\DateRangeFilter;
use App\Models\Core\File\File;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Pos\Traits\Scope\BranchOrWarehouseScope;
use App\Models\Tenant\Rules\ExpenseRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends TenantModel
{
    use HasFactory, BootTrait, CreatedByRelationship, ExpenseRules, DateRangeFilter, BranchOrWarehouseScope;

    protected $fillable = [
        'expense_area_id', 'name', 'amount', 'description', 'expense_date', 'tenant_id', 'created_by', 'branch_or_warehouse_id'
    ];


    public function expenseArea(): BelongsTo
    {
        return $this->belongsTo(ExpenseArea::class);
    }

    public function attachments()
    {
        return $this->morphMany(File::class, 'fileable')
            ->whereType('expense');
    }

    public function branchOrWarehouse(): BelongsTo
    {
        return $this->belongsTo(BranchOrWarehouse::class);
    }
}
