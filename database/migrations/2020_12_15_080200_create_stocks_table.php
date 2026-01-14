<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_or_warehouse_id')->nullable()->constrained('branch_or_warehouses');
            $table->foreignId('variant_id')->constrained();
            $table->float('avg_purchase_price')->default(0);
            $table->integer('total_purchase_qty')->default(0);
            $table->integer('total_sales_qty')->default(0);
            $table->integer('total_adjustment_qty')->default(0);
            $table->integer('total_internal_transfer_sent_qty')->default(0);
            $table->integer('total_internal_transfer_received_qty')->default(0);
            $table->integer('available_qty')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}