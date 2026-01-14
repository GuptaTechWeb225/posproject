<?php


namespace App\Models\Tenant\Rules;


trait NameValidationRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|string|max:120'
        ];
    }

    public function updateRules()
    {
        return $this->createdRules();
    }
}