<?php


namespace App\Services\Tenant\Product;

use App\Models\Pos\Product\SubCategory\SubCategory;
use App\Services\Core\BaseService;

class SubCategoryServices extends BaseService
{
    public function __construct(SubCategory $subCategory)
    {
        $this->model = $subCategory;
    }
}