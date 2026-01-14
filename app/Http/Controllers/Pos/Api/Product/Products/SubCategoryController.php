<?php

namespace App\Http\Controllers\Pos\Api\Product\Products;

use App\Filters\Tenant\SubCategoryFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\SubCategoryRequest;
use App\Models\Pos\Product\SubCategory\SubCategory;
use App\Services\Tenant\Product\SubCategoryServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
{
    public function __construct(SubCategoryServices $services, SubCategoryFilter $filter)
    {
        $this->service = $services;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->with('createdBy:id,first_name,last_name', 'categories:id,name,description,status_id')
            ->orderByDesc('id')
            ->paginate(
                request('per_page', 10)
            );
    }

    public function store(SubCategoryRequest $request)
    {
        $sub_category = array_merge($request->only('name', 'description','category_id','status_id'), [
             'created_by' => auth()->id()
            ]
        );
        $this->service->save($sub_category);

        return created_responses('sub_category');
    }


    public function show(SubCategory $subCategory): SubCategory
    {
        return $subCategory->load('createdBy:id,first_name,last_name', 'category:id,name');
    }

    public function update(SubCategoryRequest $request, SubCategory $subCategory)
    {

        DB::transaction(function () use($subCategory, $request){

            $this->service
                ->setModel($subCategory)
                ->save($request->only('name','description','status_id'));

        });

        return updated_responses('sub_category');
    }

    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        return deleted_responses('sub_category');
    }

    public function changeStatus(SubCategory $sub_category_id, Request $request)
    {
        $request->validate([
            'status_id' => 'required',
        ]);
        $sub_category_id->status_id = intval($request->status_id);
        $sub_category_id->save();

        return status_response('subcategory', strtolower(__t($sub_category_id->load('status')->status->name)));
    }
}
