<?php


namespace App\Http\Requests\Tenant;


use App\Models\Tenant\Traits\SmsDriverValidationRules;

class SmsValidationRequest extends TenantRequest
{
    use SmsDriverValidationRules;

    public function rules()
    {
        $driver = $this->only('sms_driver');

        if ($driver['sms_driver'] == 'nexmo') {
            return $this->nexmoRules();
        } elseif ($driver['sms_driver'] == 'twilio') {
            return $this->twilioRules();
        }
    }
}