<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('variants') && Schema::hasColumn('variants', 'selling_price')) {
            DB::statement("ALTER TABLE variants MODIFY selling_price double(16, 2) DEFAULT 0");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('variants') && Schema::hasColumn('variants', 'selling_price')) {
            DB::statement("ALTER TABLE variants MODIFY selling_price double(8,2) DEFAULT 0");
        }
    }
};
