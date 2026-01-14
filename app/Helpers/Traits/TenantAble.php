<?php


namespace App\Helpers\Traits;


trait TenantAble
{
    public function tenantAble()
    {
        return [
            tenant()->id,
            'App\Models\Tenant\TenantModel'
        ];
    }
}
