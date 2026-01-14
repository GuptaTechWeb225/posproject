<?php


namespace App\Services\Tenant\Setting;


use App\Services\Core\BaseService;
use App\Models\Tenant\SmsTemplate\SmsTemplate;

class SmsTemplateService extends BaseService
{

    public function __construct(SmsTemplate $smsTemplate)
    {
        $this->model = $smsTemplate;
    }

}