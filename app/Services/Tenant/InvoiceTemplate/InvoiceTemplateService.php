<?php

namespace App\Services\Tenant\InvoiceTemplate;

use App\Models\Tenant\InvoiceTemplate\InvoiceTemplate;
use App\Services\Tenant\TenantService;

class InvoiceTemplateService extends TenantService
{

    public function __construct(InvoiceTemplate $invoiceTemplate)
    {
        $this->model = $invoiceTemplate;
    }

    public function update()
    {
        $this->model->fill($this->getAttributes());

        $this->model->save();

        return $this;
    }
}