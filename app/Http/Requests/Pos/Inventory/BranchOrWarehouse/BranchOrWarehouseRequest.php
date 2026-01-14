<?php

namespace App\Http\Requests\Pos\Inventory\BranchOrWarehouse;

use App\Http\Requests\BaseRequest;
use App\Models\Pos\Inventory\BranchOrWarehouse;

class BranchOrWarehouseRequest extends BaseRequest
{
    public function rules(): array
    {
        return $this->initRules(new BranchOrWarehouse());
    }
}
