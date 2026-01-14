<?php

namespace App\Http\Controllers\Tenant\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\CategoryImportRequest;
use App\Services\Tenant\Product\CategoryImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryImportController extends Controller
{
    public function __construct(CategoryImportService $service)
    {
        $this->service = $service;
    }

    public function preview(CategoryImportRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $categories = $this->service
                ->setAttr('file', $request->file('categories'))
                ->preview();


            if (filled($categories['sanitized'])) {
                $this->service
                    ->setAttr('categories', $categories['sanitized'])
                    ->saveSanitized();
            }

            unset($categories['sanitized']);

            if (count($categories['filtered'])) {

                return $categories;
            }

            return created_responses('category');

        });

    }

    public function store(Request $request): array
    {
        $response = $this->service
            ->setAttr('categories', $request->get('categories'))
            ->store();

        if (is_array($response)) {
            return $response;
        }

        return created_responses('category');
    }
}
