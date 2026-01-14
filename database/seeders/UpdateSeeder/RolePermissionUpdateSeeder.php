<?php
namespace Database\Seeders\UpdateSeeder;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Type;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
class RolePermissionUpdateSeeder extends Seeder
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

        $this->seedNewPermissions();

        $this->enableForeignKeys();
    }

    public function seedNewPermissions(): void
    {
        $this->disableForeignKeys();
        $appId = Type::findByAlias('app')->id;

        $new_permissions = [
            [
                'name' => 'sales_invoice_generate',
                'type_id' => $appId,
                'group_name' => 'sales_invoice'
            ],
        ];

        $this->enableForeignKeys();
        Permission::query()->insert($new_permissions);
    }
}
