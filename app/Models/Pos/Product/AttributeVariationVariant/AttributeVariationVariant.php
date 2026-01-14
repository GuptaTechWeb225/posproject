<?php

namespace App\Models\Pos\Product\AttributeVariationVariant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeVariationVariant extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'attribute_variation_variant';
    protected $fillable = ['variant_id','attribute_variation_id'];

}
