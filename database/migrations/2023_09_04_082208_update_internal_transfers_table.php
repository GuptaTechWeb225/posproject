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
        if (Schema::hasTable('internal_transfers') && Schema::hasColumn('internal_transfers', 'total_transfer_cost')) {
            DB::statement("ALTER TABLE internal_transfers MODIFY total_transfer_cost double(16,2) DEFAULT 0");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('internal_transfers') && Schema::hasColumn('internal_transfers', 'total_transfer_cost')) {
            Schema::table('internal_transfers', function (Blueprint $table) {
                $table->integer('total_transfer_cost')->nullable()->default(0)->change();
            });
        }
    }
};
