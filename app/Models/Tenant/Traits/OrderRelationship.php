<?php


namespace App\Models\Tenant\Traits;


use App\Models\Core\Auth\User;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\StatusRelationship;
use App\Models\Pos\Inventory\BranchOrWarehouse;
use App\Models\Pos\Product\Tax\Tax;
use App\Models\Tenant\Branch\Branch;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Discount\Discount;
use App\Models\Tenant\Order\OrderProduct;
use App\Models\Tenant\PaymentMethod\PaymentMethod;
use App\Models\Tenant\Sales\Cash\CashRegister;
use App\Models\Tenant\Transaction\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait OrderRelationship
{
    use HasFactory, BootTrait, CreatedByRelationship, StatusRelationship;

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function branchOrWarehouse(): BelongsTo
    {
        return $this->belongsTo(BranchOrWarehouse::class, 'branch_or_warehouse_id');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }
}