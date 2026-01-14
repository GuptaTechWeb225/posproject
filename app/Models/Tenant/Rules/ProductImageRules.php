<?php


namespace App\Models\Tenant\Rules;


trait ProductImageRules
{
    public function createdRules()
    {
        return [
            'subject' => 'required'
        ];
    }

    public function updateRules()
    {
        return $this->createdRules();
    }
}