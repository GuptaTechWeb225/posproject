<?php

namespace App\Http\Controllers\Pos\Api\Product\Products;

use App\Filters\Tenant\CategoryFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Product\CategoryRequest;
use App\Models\Pos\Product\Category\Category;
use App\Services\Tenant\Product\CategoryServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct(CategoryServices $services, CategoryFilter $filter)
    {
        $this->service = $services;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->with('createdBy:id,first_name,last_name', 'subCategories.status', 'status')
            ->orderByDesc('id')
            ->paginate(
                request('per_page', 10)
            );
    }

    public function store(CategoryRequest $request)
    {
        $category = array_merge(
            $request->only('name', 'description','status_id'),
            ['created_by' => auth()->id()]
        );
        $this->service->save($category);

        return created_responses('category');
    }

    public function show(Category $category): Category
    {
        return $category->load('createdBy:id,first_name,last_name', 'subCategories.status', 'status');
    }

    public function update(CategoryRequest $request, Category $category)
    {
        DB::transaction(function () use ($category, $request) {
            $this->service
                ->setModel($category)
                ->save($request->only('name','description','status_id'));
        });

        return updated_responses('category');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return deleted_responses('category');
    }

    public function changeStatus(Category $category_id, Request $request)
    {
        $request->validate([
            'status_id' => 'required',
        ]);
        $category_id->status_id = intval($request->status_id);
        $category_id->save();

        return status_response('category', strtolower(__t($category_id->load('status')->status->name)));
    }
}
