<?php


namespace App\Models\Tenant\Rules;


trait SmsTemplateRules
{
    public function createdRules()
    {
        return [
            'content' => 'required'
        ];
    }

    public function updateRules()
    {
        return $this->createdRules();
    }
}