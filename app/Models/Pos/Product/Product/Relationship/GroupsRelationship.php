<?php

namespace App\Models\Pos\Product\Product\Relationship;

use App\Models\Pos\Product\Group\Group;

trait GroupsRelationship
{
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}