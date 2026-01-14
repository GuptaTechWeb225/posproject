<?php

namespace App\Http\Requests\Pos\Inventory\Lot;

use App\Http\Requests\BaseRequest;
use App\Models\Pos\Inventory\Lot\Lot;

class LotRequest extends BaseRequest
{
    public function rules(): array
    {
        return $this->initRules( new Lot());
    }


    public function messages(): array
    {
        return [
            'supplier_id' => 'Select a supplier',
            'branch_or_warehouse_id' => 'Select branch or warehouse',
            'status_id' => 'Select status field',

            'lotVariants.*.variant_id'=> 'Select a product',
        ];
    }
}
