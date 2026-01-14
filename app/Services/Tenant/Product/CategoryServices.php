<?php


namespace App\Services\Tenant\Product;


use App\Models\Pos\Product\Category\Category;
use App\Models\Pos\Product\SubCategory\SubCategory;
use App\Services\Core\BaseService;

class CategoryServices extends BaseService
{
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function saveSubCategory()
    {
        $subCategories = $this->getAttr('sub_category_id');

        foreach ($subCategories as $subCategory)
        {
            SubCategory::query()
                ->where('id', $subCategory)
                ->update([
                    'category_id' => $this->getModel()->id
                ]);
        }
    }
}