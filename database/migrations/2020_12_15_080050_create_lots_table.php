<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_or_warehouse_id')->nullable()->constrained('branch_or_warehouses');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('status_id')->nullable()->constrained('statuses');
            $table->integer('other_charge')->nullable()->default(0);
            $table->integer('total_amount')->nullable()->default(0)->comment('total cost for this lot');
            $table->integer('discount_amount')->nullable()->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->date('purchase_date')->nullable();
            $table->boolean('adjusted_with_stock')->default(false)->comment('value will be true or false');
            $table->string('reference_no');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('lots');
    }
}
