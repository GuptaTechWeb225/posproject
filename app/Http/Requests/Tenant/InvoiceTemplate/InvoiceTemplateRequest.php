<?php


namespace App\Http\Requests\Tenant\InvoiceTemplate;


use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Tenant\InvoiceTemplate\InvoiceTemplate;

class InvoiceTemplateRequest extends TenantRequest
{

    public function rules(): array
    {

        return $this->initRules( new InvoiceTemplate());

    }

}