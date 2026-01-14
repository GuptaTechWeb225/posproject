<?php

namespace App\Models\Tenant\Transaction;

use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Tenant\PaymentMethod\PaymentMethod;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends TenantModel
{
    use HasFactory, BootTrait, CreatedByRelationship;

    protected $fillable = [
        'payment_method_id', 'created_by','transaction_at', 'transactionable_type',
        'transactionable_id', 'amount',  'tenant_id',
    ];

    protected $casts = [
        'payment_method_id' => 'integer',
        'transactionable_id' => 'integer',
        'created_by' => 'integer',
        'tenant_id' => 'integer',
    ];

    public function paymentMethod(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function transactionable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
