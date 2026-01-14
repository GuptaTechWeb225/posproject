<?php


namespace App\Services\Tenant\Contact;


use App\Models\Tenant\Customer\Address;
use App\Services\Tenant\TenantService;

class CustomerAddressService extends TenantService
{
    public function __construct(Address $address)
    {
        $this->model = $address;
    }

    public function update()
    {
        $this->model->fill($this->getAttributes());

        $this->model->save();

        return $this;
    }

    public function findCustomer($customer)
    {
        return $this->model->where('customer_id', $customer)->get();
    }

}