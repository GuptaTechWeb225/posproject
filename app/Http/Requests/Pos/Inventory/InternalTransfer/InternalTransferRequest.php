<?php

namespace App\Http\Requests\Pos\Inventory\InternalTransfer;

use App\Http\Requests\BaseRequest;
use App\Models\Pos\Inventory\InternalTransfer;

class InternalTransferRequest extends BaseRequest
{
    public function rules(): array
    {
        return $this->initRules(new InternalTransfer());
    }

    public function messages(): array
    {
        return [
            'supplier_id' => 'Select a supplier',
            'branch_or_warehouse_from_id' => 'Select branch or warehouse to transfer from',
            'branch_or_warehouse_to_id' => 'Select branch or warehouse to transfer to',
            'status_id' => 'Select status field',

            'branch_or_warehouse_to_id.different' => 'Both branch or warehouse should not same for internal transfer',
        ];
    }

}
