<?php

namespace App\Models\Tenant\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model
{
    use HasFactory;


    protected $table = 'customer_ledger';

    protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'direction',
        'description',
        'reference_type',
        'reference_id',
        'created_by'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transaction()
    {
        return $this->hasOne(CustomerTransaction::class);
    }
}
