<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Type;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        Permission::query()->truncate();
        $appId = Type::findByAlias('app')->id;

        $permissions = [
            // custom_fields
            [
                'name' => 'view_custom_fields',
                'type_id' => $appId,
                'group_name' => 'custom_fields'
            ],
            [
                'name' => 'create_custom_fields',
                'type_id' => $appId,
                'group_name' => 'custom_fields'
            ],
            [
                'name' => 'update_custom_fields',
                'type_id' => $appId,
                'group_name' => 'custom_fields'
            ],
            [
                'name' => 'delete_custom_fields',
                'type_id' => $appId,
                'group_name' => 'custom_fields'
            ],
//            end custom_fields

//        notification_events start
            [
                'name' => 'attach_templates_notification_events',
                'type_id' => $appId,
                'group_name' => 'notification_events'
            ],
            [
                'name' => 'detach_templates_notification_events',
                'type_id' => $appId,
                'group_name' => 'notification_events'
            ],
//            notification_events end

            //log start
            [
                'name' => 'view_activity_logs',
                'type_id' => $appId,
                'group_name' => 'log'
            ],
        ];

        $this->enableForeignKeys();
        Permission::query()->insert($permissions);
    }
}
