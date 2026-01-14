<?php

namespace App\Models\Tenant\Comment;

use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends TenantModel
{
    use HasFactory;

    protected $fillable = [

        'type', 'comment', 'user_id', 'tenant_id'

    ];

    protected $hidden = [

        'commentable_type', 'commentable_id'

    ];

    public function commentable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
