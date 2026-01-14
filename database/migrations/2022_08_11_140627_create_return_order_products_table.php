<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('return_order_id')->constrained('return_orders')->cascadeOnDelete();
            $table->foreignId('stock_id')->constrained('stocks');
            $table->foreignId('variant_id')->nullable()->constrained('variants');
            $table->foreignId('order_product_id')->nullable()->constrained('products');
            $table->integer('quantity');
            $table->double('price');
            $table->double('tax_amount')->default(0.00);
            $table->enum('discount_type', ['percentage', 'flat']);
            $table->double('discount_value')->default(0.00);
            $table->double('discount_amount')->default(0.00);
            $table->double('sub_total')->default(0.00);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('return_order_products');
    }
}
