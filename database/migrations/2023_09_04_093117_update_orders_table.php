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
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'total_tax')) {
            DB::statement("ALTER TABLE orders MODIFY total_tax double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'discount')) {
            DB::statement("ALTER TABLE orders MODIFY discount double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'due_amount')) {
            DB::statement("ALTER TABLE orders MODIFY due_amount double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'paid_amount')) {
            DB::statement("ALTER TABLE orders MODIFY paid_amount double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'sub_total')) {
            DB::statement("ALTER TABLE orders MODIFY sub_total double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'grand_total')) {
            DB::statement("ALTER TABLE orders MODIFY grand_total double(16,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'change_return')) {
            DB::statement("ALTER TABLE orders MODIFY change_return double(16,2) DEFAULT 0");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'total_tax')) {
            DB::statement("ALTER TABLE orders MODIFY total_tax double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'discount')) {
            DB::statement("ALTER TABLE orders MODIFY discount double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'due_amount')) {
            DB::statement("ALTER TABLE orders MODIFY due_amount double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'paid_amount')) {
            DB::statement("ALTER TABLE orders MODIFY paid_amount double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'sub_total')) {
            DB::statement("ALTER TABLE orders MODIFY sub_total double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'grand_total')) {
            DB::statement("ALTER TABLE orders MODIFY grand_total double(8,2) DEFAULT 0");
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'change_return')) {
            DB::statement("ALTER TABLE orders MODIFY change_return double(8,2) DEFAULT 0");
        }
    }
};
