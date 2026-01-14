<?php

namespace App\Models\Tenant\Traits;

trait SmsDriverValidationRules
{
    public function nexmoRules()
    {
        return [
            'sms_sent_from' => 'required',
            'sms_driver' => 'required',
            'key' => 'required',
            'secret_key' => 'required',
        ];
    }

    public function twilioRules()
    {
        return [
            'sms_sent_from' => 'required',
            'sms_driver' => 'required',
            'auth_token' => 'required',
            'account_sid' => 'required',
        ];
    }
}