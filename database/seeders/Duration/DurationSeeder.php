<?php

namespace Database\Seeders\Duration;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Pos\Product\Duration\Duration;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class DurationSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        Duration::query()->truncate();

        $status_id = Status::query()
            ->where('name','status_active')
            ->where('type','duration')->first()->id;

        $created_by = User::query()->first()->id;

        $durations =  [
            [
                'status_id' => $status_id,
                'created_by' => $created_by,
                'type' => "Hour(s)",
                'context' => 'product'
            ],
            [
                'status_id' => $status_id,
                'created_by' => $created_by,
                'type' => "Day(s)",
                'context' => 'product'
            ],
            [
                'status_id' => $status_id,
                'created_by' => $created_by,
                'type' => "Week(s)",
                'context' => 'product'
            ],
            [
                'status_id' => $status_id,
                'created_by' => $created_by,
                'type' => "Month(s)",
                'context' => 'product'
            ],
            [
                'status_id' => $status_id,
                'created_by' => $created_by,
                'type' => "Year(s)",
                'context' => 'product'
            ],
        ];
        Duration::query()->insert($durations);

        $this->enableForeignKeys();
    }
}
