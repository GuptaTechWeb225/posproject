<?php

namespace App\Http\Requests\Pos\Inventory\Adjustment;

use App\Http\Requests\BaseRequest;
use App\Models\Pos\Inventory\Adjustment\Adjustment;

class AdjustmentRequest extends BaseRequest
{

    /**
     * @throws \App\Exceptions\GeneralException
     */
    public function rules(): array
    {
        return $this->initRules(new Adjustment());
    }

    public function messages(): array
    {
        return [
            'branch_or_warehouse_id' => 'Select a branch or warehouse',
        ];
    }

}
