<?php


namespace App\Models\Tenant\Rules;


trait GroupRules
{
    public function createdRules(): array
    {
        return [
            'name' => 'required|string|max:120|unique:groups,name',
        ];
    }

    public function updatedRules(): array
    {
        return [
            'name' => 'required|unique:groups,name,'.request()->id,
        ];
    }
}