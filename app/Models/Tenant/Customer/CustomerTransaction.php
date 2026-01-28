<?php

namespace App\Models\Tenant\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'payment_method_id',
        'cash_register_id',
        'created_by',
        'amount',
        'type',
        'note',
        'customer_ledger_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function ledger()
    {
        return $this->belongsTo(CustomerLedger::class, 'customer_ledger_id');
    }
}
