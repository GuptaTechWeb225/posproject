<?php

namespace Database\Seeders\Tenant;

use App\Models\Core\Setting\Setting;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Seeder;

class TenantSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->where('context', 'tenant')->delete();
        $settingable_type = null;

        if (class_exists(TenantModel::class)) {
            $settingable_type = get_class(new TenantModel());
        }

        Setting::query()->insert(
            array_map(function ($s) use ($settingable_type) {
                $s['context'] = 'tenant';
                $s['settingable_id'] = 1;
                $s['settingable_type'] = $settingable_type;
                return $s;
            }, array_merge(array_filter(config('settings.app'), function ($s) {
                return !in_array($s['name'], ['company_name', 'company_icon', 'company_banner', 'company_logo', 'sales_invoice_prefix',
                    'sales_invoice_suffix', 'sales_invoice_last_number', 'sales_invoice_auto_generate', 'sales_invoice_starts_from', 'sales_invoice_logo'
                ]);
            }), [
                ['name' => 'tenant_name', 'value' => config('app.name'), 'context' => 'tenant', 'autoload' => 0, 'public' => 1,]
            ]))
        );
    }
}
