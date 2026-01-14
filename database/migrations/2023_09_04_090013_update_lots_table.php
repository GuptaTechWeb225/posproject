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
        if (Schema::hasTable('lots') && Schema::hasColumn('lots', 'other_charge')) {
            DB::statement("ALTER TABLE lots MODIFY other_charge double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('lots') && Schema::hasColumn('lots', 'total_amount')) {
            DB::statement("ALTER TABLE lots MODIFY total_amount double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('lots') && Schema::hasColumn('lots', 'discount_amount')) {
            DB::statement("ALTER TABLE lots MODIFY discount_amount double(16,2) DEFAULT 0");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('lots') && Schema::hasColumn('lots', 'other_charge')) {
            Schema::table('lots', function (Blueprint $table) {
                $table->integer('other_charge')->nullable()->default(0)->change();
            });
        }
        if (Schema::hasTable('lots') && Schema::hasColumn('lots', 'total_amount')) {
            Schema::table('lots', function (Blueprint $table) {
                $table->integer('total_amount')->nullable()->default(0)->comment('total cost for this lot')->change();
            });
        }
        if (Schema::hasTable('lots') && Schema::hasColumn('lots', 'discount_amount')) {
            Schema::table('lots', function (Blueprint $table) {
                $table->integer('discount_amount')->nullable()->default(0)->change();
            });
        }
    }
};
