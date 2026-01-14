<?php


namespace App\Models\Tenant\Rules;


trait CategoryRules
{
    public function createdRules(): array
    {
        return [
            'name' => 'required|string|max:120|unique:categories,name',
        ];
    }

    public function updatedRules(): array
    {
        return [
            'name' => 'required|unique:categories,name,'.request()->id,
        ];
    }
}