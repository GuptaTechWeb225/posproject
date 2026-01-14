<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\SupplierImportRequest;
use App\Services\Tenant\Contact\SupplierImportServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierImportController extends Controller
{
    public function __construct(SupplierImportServices $services)
    {
        $this->service = $services;
    }

    public function preview(SupplierImportRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $suppliers = $this->service
                ->setAttr('file', $request->file('suppliers'))
                ->preview();


            if (filled($suppliers['sanitized'])) {

                $this->service
                    ->setAttr('suppliers', $suppliers['sanitized'])
                    ->saveSanitized();
            }
            
            unset($suppliers['sanitized']);

            if (count($suppliers['filtered'])) {

                return $suppliers;
            }

            return created_responses('supplier');


        });

    }

    public function store(Request $request): array
    {
        $response = $this->service
            ->setAttr('suppliers', $request->get('suppliers'))
            ->store();

        if (is_array($response)) {
            return $response;
        }

        return created_responses('supplier');
    }
}
