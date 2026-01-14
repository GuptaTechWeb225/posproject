<?php

namespace App\Http\Requests\Pos\Inventory\LotVariant;

use App\Http\Requests\BaseRequest;
use App\Models\Pos\Inventory\LotVariant\LotVariant;

class LotVariantRequest extends BaseRequest
{
    /**
     * @throws \App\Exceptions\GeneralException
     */
    public function rules(): array
    {
        return $this->initRules(new LotVariant());
    }
}
