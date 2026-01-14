<?php

namespace App\Http\Controllers\Tenant\Settings;


use App\Helpers\Traits\SmsHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SmsValidationRequest;
use App\Repositories\Core\Setting\SettingRepository;
use App\Services\Core\Setting\SettingService;


class SmsSettingController extends Controller
{
    use SmsHelper;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function update(SmsValidationRequest $request)
    {
        $this->service->update();

        return updated_responses('sms_setting');
    }

    public function getData()
    {
        $smsSentFrom = resolve(SettingRepository::class)->findAppSettingWithName('sms_sent_from');
        $smsDriver = resolve(SettingRepository::class)->findAppSettingWithName('sms_driver');
        $accountSid = resolve(SettingRepository::class)->findAppSettingWithName('account_sid');
        $authToken = resolve(SettingRepository::class)->findAppSettingWithName('auth_token');
        $sendAutoSms = resolve(SettingRepository::class)->findAppSettingWithName('send_auto_sms');


        if ($smsSentFrom) $smsSentFrom = $smsSentFrom->value;

        if ($smsDriver) $smsDriver = $smsDriver->value;

        if ($accountSid) $accountSid = $accountSid->value;

        if ($authToken) $authToken = $authToken->value;

        if ($sendAutoSms) $sendAutoSms = $sendAutoSms->value;

        return [
            'sms_driver' => $smsDriver,
            'account_sid' => $accountSid,
            'auth_token' => $authToken,
            'sms_sent_from' => $smsSentFrom,
            'send_auto_sms' => $sendAutoSms,
        ];

    }
}
