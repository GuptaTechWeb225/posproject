<?php


namespace App\Models\Pos\Traits\Relationship;


use App\Models\Core\Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait StatusRelationship
{
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}