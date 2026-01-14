<?php

namespace Database\Seeders\Settings;

use App\Models\Core\Setting\Setting;
use Illuminate\Database\Seeder;

class EmailSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->insert(
            [
                [
                    'name' => 'from_name',
                    'value' => encrypt(config('settings.default_mail_setup.from_name')),
                    'context' => 'default_mail_email_name',
                ],
                [
                    'name' => 'from_email',
                    'value' => encrypt(config('settings.default_mail_setup.from_email')),
                    'context' => 'default_mail_email_name',
                ],
                [
                    'name' => 'provider',
                    'value' => encrypt(config('settings.default_mail_setup.provider')),
                    'context' => 'sendmail',
                ],
                [
                    'name' => 'default_mail',
                    'value' => config('settings.default_mail_setup.default_mail'),
                    'context' => 'mail',
                ],
            ]
        );
    }
}
