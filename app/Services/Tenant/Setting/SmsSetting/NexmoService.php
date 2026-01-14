<?php

namespace App\Services\Tenant\Setting\SmsSetting;

use App\Repositories\Core\Setting\SettingRepository;
use App\Services\Tenant\TenantService;

class NexmoService extends TenantService
{
    public function updateNexmoCredentials($request)
    {
        foreach ($request->only('sms_sent_from', 'sms_driver', 'key', 'secret_key', 'test_number') as $key => $item) {

            $setting = resolve(SettingRepository::class)->createSettingInstance($key, 'sms_setting');
            $setting->name = $key;
            $setting->value = encrypt($item);
            $setting->context = 'sms_setting';
            $setting->save();
        }
    }

    public function getData()
    {
        $smsSentFrom = resolve(SettingRepository::class)->findAppSettingWithName( 'sms_sent_from', 'sms_setting');
        $smsDriver = resolve(SettingRepository::class)->findAppSettingWithName( 'sms_driver', 'sms_setting');
        $key = resolve(SettingRepository::class)->findAppSettingWithName( 'key', 'sms_setting');
        $secretKey = resolve(SettingRepository::class)->findAppSettingWithName( 'secret_key', 'sms_setting');
        $testNumber = resolve(SettingRepository::class)->findAppSettingWithName( 'test_number', 'sms_setting');

        if ($smsSentFrom) $smsSentFrom = decrypt($smsSentFrom->value);

        if ($smsDriver) $smsDriver = decrypt($smsDriver->value);

        if ($key) $key = decrypt($key->value);

        if ($secretKey) $secretKey = decrypt($secretKey->value);

        if ($testNumber) $testNumber = decrypt($testNumber->value);

        return [
            'sms_sent_from' => $smsSentFrom,
            'sms_driver' => $smsDriver,
            'key' => $key,
            'secret_key' => $secretKey,
            'test_number' => $testNumber
        ];
    }
}