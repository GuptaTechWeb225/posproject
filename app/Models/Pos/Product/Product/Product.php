<?php

namespace App\Models\Pos\Product\Product;

use App\Models\Pos\Product\Product\Relationship\ProductAllRelationship;
use App\Models\Tenant\TenantModel;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends TenantModel
{
    use HasFactory, ProductAllRelationship;

    protected $fillable = [
        'name', 'description', 'slug', 'category_id', 'sub_category_id', 'brand_id', 'unit_id', 'status_id',
        'group_id', 'created_by', 'product_type', 'duration_id', 'warranty_duration', 'note'
    ];

    protected $casts = [
        'group_id' => 'integer',
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
        'brand_id' => 'integer',
        'unit_id' => 'integer',
        'created_by' => 'integer',
        'status_id' => 'integer',
        'duration_id' => 'integer',
    ];
    /**
     * @var mixed
     */
    private $photos;

    public function createdRules(): array
    {
        return [
            'name' => 'required|string|max:120|unique:products,name',
            'image' => 'nullable|mimes:jpg,jpeg,png',
            'group_id' => 'nullable|exists:groups,id',
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'product_type' => 'required|string',
            'sku' => 'nullable|string|max:120',
            'warranty_duration' => 'nullable|numeric',
            'warranty_duration_type' => 'nullable|string',
            'variants.*.upc' => 'required|unique:variants,upc',
        ];
    }


    public function updatedRules(): array
    {
        return [
            'name' => 'required|string|max:120|unique:products,name,'.request()->get('id'),
            'image' => 'nullable|mimes:jpg,jpeg,png',
            'group_id' => 'nullable|exists:groups,id',
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'product_type' => 'required|string',
            'sku' => 'nullable|string|max:120',
            'warranty_duration' => 'nullable|numeric',
            'warranty_duration_type' => 'nullable|string',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status_id', resolve(StatusRepository::class)->productActive());
    }

}
