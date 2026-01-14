<?php


namespace App\Models\Tenant\InvoiceTemplate\Relationship;




use App\Models\Tenant\Branch\Counter\Counter;

trait CounterRelationship
{

    public function cashRegisters()
    {
        return $this->hasMany(Counter::class, 'invoice_template_id', 'id');
    }

}