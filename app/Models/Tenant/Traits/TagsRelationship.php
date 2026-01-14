<?php


namespace App\Models\Tenant\Traits;

use App\Models\Tenant\Tag\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait TagsRelationship
{
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}