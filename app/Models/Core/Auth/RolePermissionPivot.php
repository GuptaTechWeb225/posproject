<?php

namespace App\Models\Core\Auth;

use Illuminate\Database\Eloquent\Relations\Pivot;


class RolePermissionPivot extends Pivot
{
    protected $table = 'role_permission';
    public $timestamps = false;

    protected $fillable = ['role_id', 'permission_id'];

    protected $casts = [
        'meta' => 'array'
    ];
}
