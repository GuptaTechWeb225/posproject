<?php


namespace App\Http\Requests\Tenant\Stock;


use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Pos\Inventory\Lot\Lot;


class LotRequest extends TenantRequest
{
    /**
     * @throws \App\Exceptions\GeneralException
     */
    public function rules(): array
    {
        return $this->initRules(new Lot());
    }

}