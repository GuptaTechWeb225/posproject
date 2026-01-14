<?php

namespace App\Providers\Common;

use App\Config\SetMailConfig;
use App\Config\SetSettingsConfig;
use App\Services\Tenant\Setting\SettingService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            SetSettingsConfig::new(true)
                ->clear()
                ->set();

            SetMailConfig::new(true)
//                ->clear()
                ->set();
        }catch (\Exception $exception) {}
        // This needs to be set in order to access new values in `resources`/views/common/master.blade.php
//        if(Schema::hasTable('settings')) $this->updateSettings();
    }

//    public function updateSettings()
//    {
//        $settings = resolve(SettingService::class)->getFormattedTenantSettings('app');
//
//        foreach ($settings as $key => $setting) {
//            if ($setting) {
//                config()->set('settings.app.'.$key, $setting);
//            }
//        }
//    }
}
