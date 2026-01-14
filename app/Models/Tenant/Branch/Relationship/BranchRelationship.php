<?php

namespace App\Models\Tenant\Branch\Relationship;


use App\Models\Tenant\Branch\Branch;

trait BranchRelationship
{
    public function branches()
    {
        return $this->belongsToMany(
            Branch::class,
            'branch_tax',
            'tax_id',
            'branch_id'
        );
    }
}