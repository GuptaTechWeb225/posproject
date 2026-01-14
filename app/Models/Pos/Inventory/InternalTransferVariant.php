<?php

namespace App\Models\Pos\Inventory;

use App\Models\Core\BaseModel;
use App\Models\Pos\Product\Product\Variant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalTransferVariant extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'internal_transfer_id', 'variant_id', 'unit_quantity','lot_reference_no'
    ];

    protected $casts = [
        'internal_transfer_id' => 'integer',
        'variant_id' => 'integer',
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function internalTransfer(): BelongsTo
    {
        return $this->belongsTo(InternalTransfer::class);
    }
}
