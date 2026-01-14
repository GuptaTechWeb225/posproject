<?php

namespace App\Helpers\Traits;

use Twilio\Rest\Client;
use App\Repositories\Core\Setting\SettingRepository;

trait SmsHelper
{
    public function sendSms($to, $content)
    {
        $accountSid = resolve(SettingRepository::class)->findAppSettingWithName('account_sid');
        $authToken = resolve(SettingRepository::class)->findAppSettingWithName('auth_token');
        $twilioNumber = resolve(SettingRepository::class)->findAppSettingWithName('sms_sent_from');

        if (empty($accountSid->value) || empty($authToken->value)) {

            return updated_responses('sms_setting');

        } else {

            $client = new Client($accountSid->value, $authToken->value);
            return $client->messages->create($to, [
                'from' => $twilioNumber->value,
                'body' => $content]);

        }
    }

}