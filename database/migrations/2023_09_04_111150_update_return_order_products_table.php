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
        if (Schema::hasTable('return_order_products') && Schema::hasColumn('return_order_products', 'price')) {
            DB::statement("ALTER TABLE return_order_products MODIFY price double(16, 2) DEFAULT 0");
        }
        if (Schema::hasTable('return_order_products') && Schema::hasColumn('return_order_products', 'tax_amount')) {
            DB::statement("ALTER TABLE return_order_products MODIFY tax_amount double(16, 2) DEFAULT 0");
        }
        if (Schema::hasTable('return_order_products') && Schema::hasColumn('return_order_products', 'discount_value')) {
            DB::statement("ALTER TABLE return_order_products MODIFY discount_value double(16, 2) DEFAULT 0");
        }
        if (Schema::hasTable('return_order_products') && Schema::hasColumn('return_order_products', 'discount_amount')) {
            DB::statement("ALTER TABLE return_order_products MODIFY discount_amount double(16, 2) DEFAULT 0");
        }
        if (Schema::hasTable('return_order_products') && Schema::hasColumn('return_order_products', 'sub_total')) {
            DB::statement("ALTER TABLE return_order_products MODIFY sub_total double(16, 2) DEFAULT 0");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('return_order_products') && Schema::hasColumn('return_order_products', 'price')) {
            DB::statement("ALTER TABLE return_order_products MODIFY price double(8,2)");
        }
        if (Schema::hasTable('return_order_products') && Schema::hasColumn('return_order_products', 'tax_amount')) {
            DB::statement("ALTER TABLE return_order_products MODIFY tax_amount double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('return_order_products') && Schema::hasColumn('return_order_products', 'discount_value')) {
            DB::statement("ALTER TABLE return_order_products MODIFY discount_value double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('return_order_products') && Schema::hasColumn('return_order_products', 'discount_amount')) {
            DB::statement("ALTER TABLE return_order_products MODIFY discount_amount double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('return_order_products') && Schema::hasColumn('return_order_products', 'sub_total')) {
            DB::statement("ALTER TABLE return_order_products MODIFY sub_total double(8,2) DEFAULT 0");
        }
    }
};
