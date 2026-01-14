<?php

namespace App\Models\Pos\Inventory\LotVariant;

use App\Models\Core\BaseModel;
use App\Models\Pos\Inventory\Rules\LotVariantRules;
use App\Models\Pos\Product\Product\Variant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LotVariant extends BaseModel
{
    use HasFactory, LotVariantRules;

    protected $fillable = [
        'lot_id','variant_id','unit_quantity','unit_price','unit_tax_percentage',
        'total_unit_price'
    ];

    protected $casts = [
        'lot_id' => 'integer',
        'variant_id' => 'integer',
        'unit_quantity' => 'integer',
        'unit_price' => 'integer',
        'total_unit_price' => 'integer',
        'created_by' => 'integer',
    ];


    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

}
