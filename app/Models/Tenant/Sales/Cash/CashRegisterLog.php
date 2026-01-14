<?php

namespace App\Models\Tenant\Sales\Cash;

use App\Models\Core\Auth\User;
use App\Models\Core\Traits\StatusRelationship;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\PaymentMethod\PaymentMethod;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashRegisterLog extends TenantModel
{
    use HasFactory, StatusRelationship;

    protected $fillable = [
        'order_id', 'payment_method_id', 'user_id', 'cash_register_id', 'branch_or_warehouse_id',
        'opening_balance', 'closing_balance', 'cash_receives', 'cash_sales', 'difference', 'opening_time',
        'closing_time', 'opened_by', 'closed_by', 'note','status_id','is_running'
    ];

    public function branchOrWarehouse(): BelongsTo
    {
        return $this->belongsTo(BranchOrWarehouse::class, 'branch_or_warehouse_id');
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function openedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function soldBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

}
