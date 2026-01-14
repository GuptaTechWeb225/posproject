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
        if (Schema::hasTable('return_orders') && Schema::hasColumn('return_orders', 'sub_total')) {
            DB::statement("ALTER TABLE return_orders MODIFY sub_total double(16, 2) DEFAULT 0");
        }
        if (Schema::hasTable('return_orders') && Schema::hasColumn('return_orders', 'total_tax')) {
            DB::statement("ALTER TABLE return_orders MODIFY total_tax double(16, 2) DEFAULT 0");
        }
        if (Schema::hasTable('return_orders') && Schema::hasColumn('return_orders', 'discount')) {
            DB::statement("ALTER TABLE return_orders MODIFY discount double(16, 2) DEFAULT 0");
        }
        if (Schema::hasTable('return_orders') && Schema::hasColumn('return_orders', 'receive_amount')) {
            DB::statement("ALTER TABLE return_orders MODIFY receive_amount double(16, 2) DEFAULT 0");
        }
        if (Schema::hasTable('return_orders') && Schema::hasColumn('return_orders', 'change_return')) {
            DB::statement("ALTER TABLE return_orders MODIFY change_return double(16, 2) DEFAULT 0");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('return_orders') && Schema::hasColumn('return_orders', 'sub_total')) {
            DB::statement("ALTER TABLE return_orders MODIFY sub_total double(8,2)");
        }
        if (Schema::hasTable('return_orders') && Schema::hasColumn('return_orders', 'total_tax')) {
            DB::statement("ALTER TABLE return_orders MODIFY total_tax double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('return_orders') && Schema::hasColumn('return_orders', 'discount')) {
            DB::statement("ALTER TABLE return_orders MODIFY discount double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('return_orders') && Schema::hasColumn('return_orders', 'receive_amount')) {
            DB::statement("ALTER TABLE return_orders MODIFY receive_amount double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('return_orders') && Schema::hasColumn('return_orders', 'change_return')) {
            DB::statement("ALTER TABLE return_orders MODIFY change_return double(8,2) DEFAULT 0");
        }
    }
};
