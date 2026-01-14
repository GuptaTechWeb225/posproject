<?php


namespace App\Models\Tenant\Rules;


trait AttributeRules
{
    public function createdRules(): array
    {
        return [
            'name' => 'required|string|max:120|unique:attributes,name',
        ];
    }

    public function updatedRules(): array
    {
        return [
           'name' => 'required|unique:attributes,name,'.request()->id,
        ];
    }
}