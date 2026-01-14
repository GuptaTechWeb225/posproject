<?php


namespace App\Services\Tenant\Product;


use App\Models\Pos\Product\Group\Group;
use App\Services\Core\BaseService;

class GroupServices extends BaseService
{

    public function __construct(Group $group)
    {
        $this->model = $group;
    }

    public function saveGroup($options = [])
    {
        $attributes = count($options) ? $options : request()->all();

        $this->model
            ->fill($this->getFillAble($attributes))
            ->save();

        return $this;
    }

    public function addProductsGroup()
    {
        $this->model->products()
            ->attach(request()->products);

        return $this;
    }

    public function updateProductsGroup()
    {
        $this->model->products()
            ->sync(request()->products);

        return $this;

    }
}