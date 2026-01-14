<?php

namespace App\Services\Tenant\Contact;

use App\Models\Tenant\Customer\Address;
use App\Services\Tenant\TenantService;

class SupplierAddressService  extends TenantService
{
    public function __construct(Address $address)
    {
        $this->model = $address;
    }

    public function update(): SupplierAddressService
    {
        $this->model->query()
            ->where('id', $this->getAttribute('id'))
            ->update($this->getAttributes());

        return $this; 
    }

    public function findSupplier($supplier)
    {
        return $this->model->where('supplier_id', $supplier)->get();
    }

}