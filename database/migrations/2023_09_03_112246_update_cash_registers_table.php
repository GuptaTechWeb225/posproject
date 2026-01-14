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
        if (Schema::hasTable('cash_registers') && Schema::hasColumn('cash_registers', 'opening_balance')) {
            DB::statement("ALTER TABLE cash_registers MODIFY opening_balance double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('cash_registers') && Schema::hasColumn('cash_registers', 'closing_balance')) {
            DB::statement("ALTER TABLE cash_registers MODIFY closing_balance double(16,2) DEFAULT 0");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('cash_registers') && Schema::hasColumn('cash_registers', 'opening_balance')) {
            Schema::table('cash_registers', function (Blueprint $table) {
                $table->double('opening_balance')->default(0)->change();
            });
        }
        if (Schema::hasTable('cash_registers') && Schema::hasColumn('cash_registers', 'closing_balance')) {
            Schema::table('cash_registers', function (Blueprint $table) {
                $table->double('closing_balance')->default(0)->change();
            });
        }
    }
};
