<?php

namespace App\Models\Pos\Product\Tag;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'color', 'created_by', 'status_id'
    ];
}
