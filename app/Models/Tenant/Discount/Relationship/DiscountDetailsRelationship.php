<?php


namespace App\Models\Tenant\Discount\Relationship;


use App\Models\Tenant\Discount\Discount;

trait DiscountDetailsRelationship
{
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}