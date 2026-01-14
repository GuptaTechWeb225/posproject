<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Filters\Tenant\AddressFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\AddressRequest;
use App\Models\Tenant\Customer\Address;
use App\Services\Tenant\Contact\CustomerAddressService;
use App\Services\Tenant\Contact\SupplierAddressService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AddressController extends Controller
{
    public SupplierAddressService $supplierAddressService;

    public function __construct(CustomerAddressService $service, AddressFilter $filter, SupplierAddressService $supplierAddressService)
    {
        $this->service = $service;
        $this->filter = $filter;
        $this->supplierAddressService = $supplierAddressService;
    }

    public function index($type): LengthAwarePaginator
    {
        return $this->service
            ->select('id', 'customer_id', 'supplier_id', 'name', 'country_id', 'city', 'zip_code', 'area', 'details', 'tenant_id')
            ->filters($this->filter)
            ->where($type . "_id", '!=', null)
            ->with($type)
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(AddressRequest $request)
    {
        $this->service
            ->save(
                $request->only(
                    'customer_id',
                    'supplier_id',
                    'name',
                    'country_id',
                    'city',
                    'zip_code',
                    'area',
                    'details'
                )
            );
        return created_responses('address');

    }

    public function show($type, Address $address): Address
    {
        return $address;
    }


    public function update($type, AddressRequest $request, Address $address)
    {
        $this->service
            ->setModel($address)
            ->save($request->only(
                'customer_id',
                'supplier_id',
                'name',
                'country_id',
                'city',
                'zip_code',
                'area',
                'details'
            ));
        return updated_responses('address', ['address' => $address]);
    }

    public function destroy($type, Address $address): array
    {
        $address->delete();

        return deleted_responses('address');
    }

    public function customerById($customer)
    {
        return $this->service
            ->findCustomer($customer);
    }

    public function addressArea(): object
    {
        return $this->service->whereNotNull('area')->get(['area']);
    }

    public function addressCity(): object
    {
        return $this->service->whereNotNull('city')->get(['city']);
    }

    public function supplierAddresses($supplier)
    {
        return $this->supplierAddressService->findSupplier($supplier);
    }

}
