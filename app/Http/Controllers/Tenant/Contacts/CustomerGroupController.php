<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Exceptions\GeneralException;
use App\Filters\Tenant\CustomerGroupFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\CustomerGroupRequest;
use App\Services\Tenant\Contact\CustomerGroupService;
use App\Models\Tenant\Customer\CustomerGroup;
use  Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerGroupController extends Controller
{

    public function __construct(CustomerGroupService $service, CustomerGroupFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index(): LengthAwarePaginator
    {
        return $this->service
            ->filters($this->filter)
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(CustomerGroupRequest $request)
    {
        $this->service
            ->checkAndResetDefault(new CustomerGroup(), $request->is_default)
            ->save(
                $request->only('name', 'is_default', 'tenant_id')
            );

        return created_responses('customer_group');
    }

    public function show(CustomerGroup $customerGroup)
    {
        return $customerGroup;
    }

    public function update(CustomerGroup $customerGroup, CustomerGroupRequest $request)
    {
        if ($request->is_default == 1 && $customerGroup->is_default == 0) {
            $this->service
                ->checkAndResetDefault(new CustomerGroup(), $request->is_default);
        }

        $this->service
            ->setModel($customerGroup)
            ->save(
                $request->only('name', 'is_default', 'tenant_id')
            );

        return updated_responses('customer_group');

    }

    public function destroy(CustomerGroup $customerGroup)
    {
        $customerCount = $customerGroup->customers->count();

        throw_if($customerCount > 0, new GeneralException(__t('the_customer_group_is_in_use')));

        throw_if($customerGroup->is_default, new GeneralException(__t('the_customer_group_is_in_use')));

        $customerGroup->delete();

        return deleted_responses('customer_group');
    }
}
