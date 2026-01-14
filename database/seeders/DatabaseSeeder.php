<?php

namespace Database\Seeders;

use Database\Seeders\App\PermissionAppSeeder;
use Database\Seeders\App\SmsTemplateSeeder;
use Database\Seeders\Auth\BranchManagerPermissionSeeder;
use Database\Seeders\Auth\CashierPermissionSeeder;
use Database\Seeders\Auth\ManagerPermissionSeeder;
use Database\Seeders\Auth\WarehouseManagerPermissionSeeder;
use Database\Seeders\Customer\CustomerSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\Auth\TypeSeeder;
use Illuminate\Database\Eloquent\Model;
use Database\Seeders\Status\StatusSeeder;
use Database\Seeders\Auth\UserTableSeeder;
use Database\Seeders\Traits\TruncateTable;
use Database\Seeders\App\SettingTableSeeder;
use Database\Seeders\Auth\UserRoleTableSeeder;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Builder\CustomFieldTypeSeeder;
use Database\Seeders\App\NotificationSettingsSeeder;
use Database\Seeders\App\NotificationTemplateSeeder;
use Database\Seeders\Auth\PermissionRoleTableSeeder;
use Database\Seeders\App\NotificationEventTableSeeder;
use Database\Seeders\App\NotificationChannelTableSeeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        Artisan::call('cache:clear');
        Model::unguard();

        activity()->withoutLogs(function () {
            $this->disableForeignKeys();
            $this->call(StatusSeeder::class);
            $this->call(UserTableSeeder::class);
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
            $this->call(CustomerSeeder::class);
            $this->call(SmsTemplateSeeder::class);


        });

        $this->enableForeignKeys();
        Model::reguard();
    }
}
