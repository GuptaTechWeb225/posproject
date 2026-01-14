<?php

namespace App\Models\Tenant\Branch\Relationship;

use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ManagerRelationship
{
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }
}