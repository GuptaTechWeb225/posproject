<?php

namespace App\Config;

use App\Helpers\Core\Traits\InstanceCreator;
use App\Services\Core\Setting\SettingService;
use Illuminate\Support\Facades\Artisan;

class SetSettingsConfig
{
    use InstanceCreator;

    public function clear()
    {
        Artisan::call('config:clear');
        return $this;
    }

    public function set()
    {
        $settings = cache()->remember('app-settings-global', 84000, function () {
            return resolve(SettingService::class)
                ->getFormattedSettings();
        });

        foreach ($settings as $key => $setting) {
            if ($setting) {
                config()->set('settings.application.'.$key, $setting);
            }
        }

        return true;
    }


}
