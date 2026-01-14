<?php

namespace App\Models\Pos\Product\Attribute;

use App\Models\Pos\Product\Product\Variant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttributeVariation extends Model
{
    use HasFactory;

    protected $fillable = ['attribute_id', 'name'];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class, 'attribute_variation_variant');
    }
}
