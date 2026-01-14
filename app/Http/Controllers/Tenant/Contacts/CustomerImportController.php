<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\CustomerImportRequest;
use App\Services\Tenant\Contact\CustomerImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerImportController extends Controller
{

    public function __construct(CustomerImportService $service)
    {
        $this->service = $service;
    }

    public function preview(CustomerImportRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $customers = $this->service
                ->setAttr('file', $request->file('customers'))
                ->preview();

            if (filled($customers['sanitized'])) {
                $this->service
                    ->setAttr('sanitized', $customers['sanitized'])
                    ->saveSanitized();
            }
            unset($customers['sanitized']);

            if (count($customers['filtered'])) {
                return $customers;
            }

            return created_responses('customer');

        });
    }

    public function store(Request $request): array
    {
        $response = $this->service
            ->setAttr('customers', $request->get('customers'))
            ->store();

        if (is_array($response)) {
            return $response;
        }

        return created_responses('customer');
    }
}
