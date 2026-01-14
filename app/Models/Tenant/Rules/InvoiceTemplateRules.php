<?php


namespace App\Models\Tenant\Rules;


trait InvoiceTemplateRules
{
    public function createdRules()
    {
        return [
            'name' => 'required|unique:invoice_templates,name',
            'type' => 'required'
        ];
    }

    public function updatedRules()
    {
        return [
            'name' => 'required|unique:invoice_templates,name,'.request()->id,
            'type' => 'required'
        ];
    }
}