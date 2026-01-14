<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_or_warehouse_id')->constrained('branch_or_warehouses');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('stock_id')->constrained('stocks');
            $table->foreignId('variant_id')->constrained('variants');
            $table->foreignId('order_product_id')->constrained('products');
            $table->timestamp('ordered_at');
            $table->double('avg_purchase_price')->comment('average purchase price')->default(0);
            $table->double('selling_price')->comment('average purchase price')->default(0);
            $table->double('price')->comment('calculated selling price');
            $table->integer('quantity');
            $table->double('tax_amount')->default(0.00);
            $table->enum('discount_type', ['percentage', 'flat'])->nullable();
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
        Schema::dropIfExists('order_products');
    }
}
