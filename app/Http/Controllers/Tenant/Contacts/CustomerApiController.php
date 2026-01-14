<?php

namespace App\Http\Controllers\Tenant\Contacts;


use App\Http\Controllers\Controller;
use App\Models\Tenant\Customer\Customer;
use App\Models\Tenant\Customer\CustomerGroup;
use App\Services\Tenant\Export\CustomerExportService;

class CustomerApiController extends Controller
{
    public function __construct(CustomerExportService $service)
    {

        $this->service = $service;
    }

    public function index()
    {
        return CustomerGroup::query()->get(['id', 'name', 'is_default']);
    }

    public function customerCount()
    {
        return Customer::count();
    }


}
