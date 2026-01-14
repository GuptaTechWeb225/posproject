<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_or_warehouse_id')->constrained('branch_or_warehouses');
            $table->foreignId('cash_register_id')->constrained('cash_registers');
            $table->foreignId('tax_id')->nullable()->constrained('taxes');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('discount_id')->nullable()->constrained('discounts');
            $table->enum('payment_type', ['full_payment', 'partial_payment'])->default('full_payment');
            $table->string('invoice_number', '120');
            $table->string('last_invoice_number', '120');
            $table->float('total_tax')->default(0);
            $table->enum('discount_type', ['percentage', 'flat'])->nullable()->comment('`type` is how much amount can get discount customers');
            $table->float('discount_value')->default(0);
            $table->float('discount')->default(0);
            $table->float('due_amount')->default(0);
            $table->float('paid_amount')->default(0);
            $table->float('sub_total')->default(0);
            $table->float('grand_total')->default(0);
            $table->float('change_return')->default(0);
            $table->text('note')->nullable();
            $table->text('payment_note')->nullable();
            $table->timestamp('ordered_at');
            $table->foreignId('status_id')->constrained('statuses');
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
        Schema::dropIfExists('orders');
    }
}
