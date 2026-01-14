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
        if (Schema::hasTable('lot_variants') && Schema::hasColumn('lot_variants', 'unit_quantity')) {
            DB::statement("ALTER TABLE lot_variants MODIFY unit_quantity double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('lot_variants') && Schema::hasColumn('lot_variants', 'unit_price')) {
            DB::statement("ALTER TABLE lot_variants MODIFY unit_price double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('lot_variants') && Schema::hasColumn('lot_variants', 'total_unit_price')) {
            DB::statement("ALTER TABLE lot_variants MODIFY total_unit_price double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('lot_variants') && Schema::hasColumn('lot_variants', 'unit_tax_percentage')) {
            DB::statement("ALTER TABLE lot_variants MODIFY unit_tax_percentage double(16,2) DEFAULT 0");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('lot_variants') && Schema::hasColumn('lot_variants', 'unit_quantity')) {
            Schema::table('lot_variants', function (Blueprint $table) {
                $table->integer('unit_quantity')->default(1)->change();
            });
        }
        if (Schema::hasTable('lot_variants') && Schema::hasColumn('lot_variants', 'unit_price')) {
            Schema::table('lot_variants', function (Blueprint $table) {
                $table->integer('unit_price')->default(0)->change();
            });
        }
        if (Schema::hasTable('lot_variants') && Schema::hasColumn('lot_variants', 'total_unit_price')) {
            Schema::table('lot_variants', function (Blueprint $table) {
                $table->integer('total_unit_price')->default(0)->change();
            });
        }
        if (Schema::hasTable('lot_variants') && Schema::hasColumn('lot_variants', 'unit_tax_percentage')) {
            Schema::table('lot_variants', function (Blueprint $table) {
                $table->integer('unit_tax_percentage')->default(0)->change();
            });
        }
    }
};
