<?php

namespace Database\Seeders;

use App\Models\Tenant\Customer\CustomerGroup;
use Database\Seeders\App\NotificationChannelTableSeeder;
use Database\Seeders\App\NotificationEventTableSeeder;
use Database\Seeders\App\NotificationSettingsSeeder;
use Database\Seeders\App\NotificationTemplateSeeder;
use Database\Seeders\App\PermissionAppSeeder;
use Database\Seeders\App\SettingTableSeeder;
use Database\Seeders\App\SmsTemplateSeeder;
use Database\Seeders\Auth\BranchManagerPermissionSeeder;
use Database\Seeders\Auth\CashierPermissionSeeder;
use Database\Seeders\Auth\ManagerPermissionSeeder;
use Database\Seeders\Auth\PermissionRoleTableSeeder;
use Database\Seeders\Auth\TypeSeeder;
use Database\Seeders\Auth\UserRoleTableSeeder;
use Database\Seeders\Auth\WarehouseManagerPermissionSeeder;
use Database\Seeders\Builder\CustomFieldTypeSeeder;
use Database\Seeders\Country\CountrySeeder;
use Database\Seeders\Customer\CustomerSeeder;
use Database\Seeders\Duration\DurationSeeder;
use Database\Seeders\Payment\PaymentMethodSeeder;
use Database\Seeders\Sales\InvoiceSeeder;
use Database\Seeders\Status\StatusSeeder;
use Database\Seeders\Tax\TaxSeeder;
use Database\Seeders\Tenant\AttributeTableSeeder;
use Database\Seeders\Tenant\AttributeVariationTableSeeder;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SetupSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        activity()->withoutLogs(function () {
            $this->disableForeignKeys();

            //-------------------------------------------------
            //start database seeder
            //-------------------------------------------------
            $this->call(StatusSeeder::class);
            $this->call(TypeSeeder::class);

            $this->call(PermissionAppSeeder::class);
            $this->call(PermissionRoleTableSeeder::class);
            $this->call(UserRoleTableSeeder::class);

            //------------------------------------
            //Default permission for default role
            //------------------------------------
            $this->call(ManagerPermissionSeeder::class);
            $this->call(WarehouseManagerPermissionSeeder::class);
            $this->call(BranchManagerPermissionSeeder::class);
            $this->call(CashierPermissionSeeder::class);


            $this->call(SettingTableSeeder::class);
            $this->call(CustomFieldTypeSeeder::class);
            $this->call(NotificationChannelTableSeeder::class);
            $this->call(NotificationEventTableSeeder::class);
            $this->call(NotificationSettingsSeeder::class);
            $this->call(NotificationTemplateSeeder::class);
            //-------------------------------------------------
            //end database seeder
            //-------------------------------------------------
            CustomerGroup::query()->create([
                'name' => 'Default',
                'is_default' => 1,
                'tenant_id' => 1
            ]);

            $this->call(TaxSeeder::class);
            $this->call(PaymentMethodSeeder::class);
            $this->call(DurationSeeder::class);
            $this->call(CountrySeeder::class);
            $this->call(SmsTemplateSeeder::class);

            $this->call(AttributeTableSeeder::class);
            $this->call(AttributeVariationTableSeeder::class);

            //seed invoice default template
            $this->call(InvoiceSeeder::class);

            $this->call(CustomerSeeder::class);

            $this->enableForeignKeys();
        });

        Model::reguard();
    }
}
