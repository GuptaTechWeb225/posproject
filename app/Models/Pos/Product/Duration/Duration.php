<?php

namespace App\Models\Pos\Product\Duration;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Duration extends BaseModel
{
    use HasFactory, BootTrait;

    protected $fillable = [
        'type', 'context', 'created_by','status_id','tenant_id'
    ];

    protected $casts = [
        'status_id' => 'integer',
        'created_by' => 'integer',
    ];

    public function createdRules()
    {
        return [
            'context' => 'required|string|max:120',
            'type' => 'required|string|max:120',
            'status_id' => 'required',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}
