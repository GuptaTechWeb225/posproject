<?php


namespace App\Http\Requests\Tenant\SmsTemplate;


use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Tenant\SmsTemplate\SmsTemplate;

class SmsTemplateRequest extends TenantRequest
{

    /**
     * @throws \App\Exceptions\GeneralException
     */
    public function rules(): array
    {
        return $this->initRules(new SmsTemplate());
    }

}