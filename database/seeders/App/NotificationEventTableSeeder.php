<?php
namespace Database\Seeders\App;

use App\Models\Core\Auth\Type;
use App\Models\Core\Setting\NotificationEvent;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class NotificationEventTableSeeder extends Seeder
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
        $appTypeId = Type::findByAlias('tenant')->id;
        $events = [
            [
                'name' => 'user_invitation_canceled',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'user_invitation',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'password_reset',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'invoice_sent',
                'type_id' => $appTypeId,
            ],
        ];

        NotificationEvent::query()->truncate();
        NotificationEvent::query()->insert($events);
        $this->enableForeignKeys();
    }
}
