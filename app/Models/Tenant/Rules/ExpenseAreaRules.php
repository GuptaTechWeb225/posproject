<?php


namespace App\Models\Tenant\Rules;


trait ExpenseAreaRules
{
    public function createdRules()
    {
        return [
            'name' => 'required',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}