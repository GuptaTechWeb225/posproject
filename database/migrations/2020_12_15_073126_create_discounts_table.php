<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_or_warehouse_id')->constrained('branch_or_warehouses');
            $table->string('name', 140);
            $table->enum('discount_type', ['individual', 'flat_discount'])->comment('`discount_type` is it per product or all products')->default('individual');
            $table->enum('type', ['percentage', 'flat'])->comment('`type` is how much amount can get discount customers');
            $table->float('value');
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->longText('note')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('published_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('status_id')->constrained('statuses');
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
        Schema::dropIfExists('discounts');
    }
}
