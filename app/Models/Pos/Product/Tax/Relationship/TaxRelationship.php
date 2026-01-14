<?php

namespace App\Models\Pos\Product\Tax\Relationship;


use App\Models\Pos\Product\Tax\Tax;

trait TaxRelationship
{
    public function taxes()
    {
        return $this->belongsToMany(
            Tax::class,
            'branch_tax',
            'branch_id',
            'tax_id'
        );
    }
}