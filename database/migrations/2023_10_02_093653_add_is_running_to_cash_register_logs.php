<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('cash_register_logs') && !Schema::hasColumn('cash_register_logs', 'is_running')) {
            Schema::table('cash_register_logs', function (Blueprint $table) {
                $table->boolean('is_running')->default(false)->after('status_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('cash_register_logs') && Schema::hasColumn('cash_register_logs', 'is_running')) {
            Schema::table('cash_register_logs', function (Blueprint $table) {
                $table->dropColumn('is_running');
            });
        }
    }
};
