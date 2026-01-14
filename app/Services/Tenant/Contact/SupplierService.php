<?php

namespace App\Services\Tenant\Contact;

use App\Models\Tenant\Supplier\Supplier;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\TenantService;

class SupplierService extends TenantService
{
    public function __construct(Supplier $supplier)
    {
        $this->model = $supplier;
    }

    public function store(): Supplier
    {
        $this->model = $this->save(
            $this->getAttrs('first_name', 'last_name', 'supplier_no', 'name')
        );

        return $this->model;
    }

    public function storeAddress()
    {
        $addresses = $this->getAttr('address_information_details');
        $addressInfo = [];

        foreach ($addresses as $key => $address) {
            $addressInfo[] = $this->addressInfo($address, $this->store()->id);
        }

        $this->store()
            ->addresses()
            ->insert($addressInfo);
    }

    public function storeContacts()
    {
        $emailDetails = $this->getAttr('email_details');
        $this->storeContactsEmail($emailDetails, $this->model->id);

        $phoneNumberDetails = $this->getAttr('phone_number_details');
        $this->storeContactsPhoneNumber($phoneNumberDetails, $this->model->id);
    }

    private function storeContactsEmail($emailDetails, $supplier_id): void
    {
        $emailInfo = [];
        foreach ($emailDetails as $key => $emailData) {
            if (array_key_first($emailDetails[$key]) === 'email') {
                $emailInfo[] = $this->contactInfo(
                    array_key_first($emailDetails[$key]),
                    $emailData['email'],
                    $emailData['email_type'] ?? null,
                    $supplier_id
                );
            }
        }
        $this->store()->contacts()->insert($emailInfo);
    }

    private function storeContactsPhoneNumber($phoneNumberDetails, $supplier_id): void
    {
        $phoneNumberInfo = [];
        foreach ($phoneNumberDetails as $key => $phoneNumberData) {
            if (array_key_first($phoneNumberDetails[$key]) === 'phone_number') {
                $phoneNumberInfo[] = $this->contactInfo(
                    array_key_first($phoneNumberDetails[$key]),
                    $phoneNumberData['phone_number'],
                    $phoneNumberData['phone_number_type'] ?? null,
                    $supplier_id
                );
            }
        }

        $this->store()->contacts()->insert($phoneNumberInfo);
    }

    private function contactInfo($key, $value, $type, $supplier_id): array
    {
        return [

            'contactable_id' => $supplier_id,
            'contactable_type' => Supplier::class,
            'name' => $key,
            'value' => $value,
            'type' => $type,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    private function addressInfo($address, $supplier_id): array
    {
        return [
            'supplier_id' => $supplier_id,
            'name' => $address['name'],
            'country_id' => $address['country_id'] ?? null,
            'city' => $address['city'] ?? null,
            'zip_code' => $address['zip_code'] ?? null,
            'area' => $address['area'] ?? null,
            'details' => $address['details'],
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function saveContacts(): void
    {
        $contacts = array_map(function ($name) {
            if ($this->getAttr($name)) {
                return [
                    'contactable_id' => $this->model->id,
                    'contactable_type' => Supplier::class,
                    'name' => $name,
                    'value' => $this->getAttr($name),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }, array_keys($this->getAttrs('phone_number', 'address', 'company')));

        $this->model
            ->contacts()
            ->insert($contacts);
    }

    public function updateContacts()
    {
        $this->getModel()->contacts()->delete();

        $emailDetails = $this->getAttr('email_details');
        $this->storeContactsEmail($emailDetails, $this->getModel()->id);

        $phoneNumberDetails = $this->getAttr('phone_number_details');
        $this->storeContactsPhoneNumber($phoneNumberDetails, $this->getModel()->id);
    }

    public function updateAddress(): void
    {
        $this->getModel()->addresses()->delete();

        $addresses = $this->getAttr('address_information_details');

        $addressInfo = [];

        foreach ($addresses as $key => $address) {
            $addressInfo[] = $this->addressInfo($address, $this->getModel()->id);
        }

        $this->getModel()
            ->addresses()
            ->insert($addressInfo);
    }


    public function changeStatus(): bool
    {
        $get_status = $this->getAttr('status');

        if (json_decode($get_status) === true){
            $status = resolve(StatusRepository::class)->supplierActive();
        }else{
            $status = resolve(StatusRepository::class)->supplierInactive();
        }

        $this->getModel()->update([
            'status_id' => $status
        ]);

        return true;
    }


}