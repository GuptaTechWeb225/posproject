<?php


namespace App\Models\Tenant\Traits;


use App\Models\Tenant\Comment\Comment;

trait CommentRelationship
{
    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}