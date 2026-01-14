<?php


namespace App\Models\Tenant\Branch\Relationship;


use App\Models\Tenant\Branch\Branch;

trait BranchCounterRelationship
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}