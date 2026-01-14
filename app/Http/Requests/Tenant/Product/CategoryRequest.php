<?php

namespace App\Http\Requests\Tenant\Product;

use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Pos\Product\Category\Category;


class CategoryRequest extends TenantRequest
{

    /**
     * @throws \App\Exceptions\GeneralException
     */
    public function rules(): array
    {
        return $this->initRules(new Category());
    }
}
