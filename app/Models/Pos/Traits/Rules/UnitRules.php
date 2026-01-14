<?php


namespace App\Models\Pos\Traits\Rules;


trait UnitRules
{
    public function createdRules(): array
    {
        return [
            'name' => 'required|string|max:120|unique:units,name',
            'status_id' => 'required|exists:statuses,id',
        ];
    }

    public function updatedRules(): array
    {

        return [
            'name' => 'required|unique:units,name,'.request()->id,
            'status_id' => 'required|exists:statuses,id',
        ];
    }
}