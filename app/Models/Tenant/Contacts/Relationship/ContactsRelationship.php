<?php

namespace App\Models\Tenant\Contacts\Relationship;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait ContactsRelationship
{
    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }
}