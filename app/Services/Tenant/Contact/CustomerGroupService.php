<?php

namespace App\Services\Tenant\Contact;

use App\Models\Tenant\Customer\CustomerGroup;
use App\Services\Tenant\TenantService;

class CustomerGroupService extends TenantService
{
    public function __construct(CustomerGroup $customer)
    {
        $this->model = $customer;
    }

    public function update()
    {

        $this->model->fill($this->getAttributes());

        $this->model->save();

        return $this;
    }
}
