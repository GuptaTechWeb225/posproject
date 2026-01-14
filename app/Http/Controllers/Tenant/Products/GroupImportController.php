<?php

namespace App\Http\Controllers\Tenant\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\GroupImportRequest;
use App\Services\Tenant\Product\ProductGroupImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupImportController extends Controller
{
    public function __construct(ProductGroupImportService $service)
    {
        $this->service = $service;
    }

    public function preview(GroupImportRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $groups = $this->service
                ->setAttr('file', $request->file('productGroups'))
                ->preview();

            if (filled($groups['sanitized'])) {

                $this->service
                    ->setAttr('groups', $groups['sanitized'])
                    ->saveSanitized();
            }

            unset($groups['sanitized']);

            if (count($groups['filtered'])) {
                return $groups;
            }

            return created_responses('group');

        });
    }


    public function store(Request $request): array
    {
        $response = $this->service
            ->setAttr('productGroups', $request->get('productGroups'))
            ->store();

        if (is_array($response)) {

            return $response;
        }

        return created_responses('group');
    }

}
