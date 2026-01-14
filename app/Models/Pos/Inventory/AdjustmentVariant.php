<?php

namespace App\Models\Pos\Inventory;

use App\Models\Pos\Product\Product\Variant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdjustmentVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'adjustment_id', 'variant_id', 'created_by','unit_quantity', 'adjustment_type'
    ];

    protected $casts = [
        'adjustment_id' => 'integer',
        'variant_id' => 'integer',
        'created_by' => 'integer',
        'unit_quantity' => 'integer',
        'adjustment_type' => 'string'
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }
}
