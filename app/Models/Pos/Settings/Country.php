<?php

namespace App\Models\Pos\Settings;

use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends TenantModel
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'status_id'
    ];
}
