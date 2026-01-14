<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_or_warehouse_id')->constrained('branch_or_warehouses');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('reference_number', '120')->nullable();
            $table->string('invoice_number', '120');
            $table->string('last_invoice_number', '120');
            $table->string('order_invoice_number', '120');
            $table->enum('type', ['partial', 'all'])->default('all');
            $table->float('sub_total');
            $table->float('total_tax')->default(0);
            $table->float('discount')->default(0);
            $table->float('receive_amount')->default(0);
            $table->float('change_return')->default(0);
            $table->text('note')->nullable();
            $table->timestamp('returned_at');
            $table->foreignId('created_by')->constrained('users');
            $table->unsignedBigInteger('tenant_id')->default(1);
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
        Schema::dropIfExists('return_orders');
    }
}
