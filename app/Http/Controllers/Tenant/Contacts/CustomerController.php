<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Filters\Tenant\CustomerFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\CustomerRequest;
use App\Models\Tenant\Customer\Customer;
use App\Services\Tenant\Contact\CustomerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerController extends Controller
{
    public function __construct(CustomerService $service, CustomerFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    private function relationship(): array
    {
        return [
            'customerGroup:id,name',
            'addresses',
            'emails',
            'phoneNumbers',
            'profilePicture',
            'createdBy:id,first_name,last_name',
        ];
    }

    public function index(): LengthAwarePaginator
    {
        return $this->service
            ->filters($this->filter)
            ->with($this->relationship())
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(CustomerRequest $request): array
    {

        DB::transaction(function () use ($request) {
            $this->service
                ->setAttrs($request->only('first_name', 'last_name', 'tin', 'customer_group_id','email'))
                ->store();

            $this->service
                ->setAttrs($request->only('email_details', 'phone_number_details'))
                ->storeContacts();

            if ($request->get('address_information_details')) {
                $this->service
                    ->setAttrs($request->only('address_information_details'))
                    ->storeAddress();
            }
        });

        return created_responses('customer');

    }

    public function show(Customer $customer): object
    {
        $contacts = $customer->contacts->groupBy('name');

        return $customer->setRelation('contacts', $contacts)->load(['customerGroup', 'addresses']);
    }


    public function update(CustomerRequest $request, Customer $customer): array
    {
        DB::transaction(function () use ($customer, $request) {

            $customer->update(
                $request->only('first_name', 'last_name', 'tin', 'customer_group_id', 'created_by', 'tenant_id','email')
            );

            $this->service
                ->setModel($customer)
                ->setAttrs($request->only('email_details', 'phone_number_details'))
                ->updateContacts();

            if ($request->get('address_information_details')) {
                $this->service
                    ->setModel($customer)
                    ->setAttrs($request->only('address_information_details'))
                    ->updateAddress();
            }

        });

        return updated_responses('customer');
    }

    public function destroy(Customer $customer): array
    {
        $this->service
            ->setModel($customer)
            ->deleteCustomer();

        return deleted_responses('customer');
    }

}
