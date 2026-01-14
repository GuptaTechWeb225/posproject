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
        if (Schema::hasTable('cash_register_logs') && Schema::hasColumn('cash_register_logs', 'opening_balance')) {
            DB::statement("ALTER TABLE cash_register_logs MODIFY opening_balance double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('cash_register_logs') && Schema::hasColumn('cash_register_logs', 'closing_balance')) {
            DB::statement("ALTER TABLE cash_register_logs MODIFY closing_balance double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('cash_register_logs') && Schema::hasColumn('cash_register_logs', 'cash_receives')) {
            DB::statement("ALTER TABLE cash_register_logs MODIFY cash_receives double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('cash_register_logs') && Schema::hasColumn('cash_register_logs', 'cash_sales')) {
            DB::statement("ALTER TABLE cash_register_logs MODIFY cash_sales double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('cash_register_logs') && Schema::hasColumn('cash_register_logs', 'difference')) {
            DB::statement("ALTER TABLE cash_register_logs MODIFY difference double(16,2) DEFAULT 0");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('cash_register_logs') && Schema::hasColumn('cash_register_logs', 'opening_balance')) {
            Schema::table('cash_register_logs', function (Blueprint $table) {
                $table->double('opening_balance')->default(0)->change();
            });
        }
        if (Schema::hasTable('cash_register_logs') && Schema::hasColumn('cash_register_logs', 'closing_balance')) {
            Schema::table('cash_register_logs', function (Blueprint $table) {
                $table->double('closing_balance')->default(0)->change();
            });
        }
        if (Schema::hasTable('cash_register_logs') && Schema::hasColumn('cash_register_logs', 'cash_receives')) {
            Schema::table('cash_register_logs', function (Blueprint $table) {
                $table->double('cash_receives')->default(0)->change();
            });
        }
        if (Schema::hasTable('cash_register_logs') && Schema::hasColumn('cash_register_logs', 'cash_sales')) {
            Schema::table('cash_register_logs', function (Blueprint $table) {
                $table->double('cash_sales')->default(0)->change();
            });
        }
        if (Schema::hasTable('cash_register_logs') && Schema::hasColumn('cash_register_logs', 'difference')) {
            Schema::table('cash_register_logs', function (Blueprint $table) {
                $table->double('difference')->default(0)->change();
            });
        }
    }
};
