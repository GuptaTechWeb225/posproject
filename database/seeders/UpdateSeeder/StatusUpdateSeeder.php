<?php
namespace Database\Seeders\UpdateSeeder;

use App\Models\Core\Status;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
class StatusUpdateSeeder extends Seeder
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
        $statuses = [
//            [
//                'name' => 'status_unverified',
//                'type' => 'user',
//                'class' => 'warning'
//            ],
        ];

        if(count($statuses)) {
            Status::query()->insert($statuses);
        }
        $this->enableForeignKeys();
    }
}
