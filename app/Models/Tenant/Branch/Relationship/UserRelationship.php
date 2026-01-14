<?php

namespace App\Models\Tenant\Branch\Relationship;

use App\Models\Core\Auth\User;

trait UserRelationship
{
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}