<?php


namespace App\Models\Pos\Product\Brand;

trait BrandRules
{
    public function createdRules(): array
    {
        return [
            'name' => 'required|string|max:120|unique:brands,name',
            'description' => 'nullable|string',
        ];
    }

    public function updatedRules(): array
    {
        return [
            'name' => 'required|unique:brands,name,'.request()->id,
            'description' => 'nullable|string',
        ];
    }
}