<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_area_id')->constrained();
            $table->foreignId('created_by')->constrained('users');
            $table->string('name');
            $table->double('amount', 8, 2)->default(0);
            $table->text('description')->nullable();
            $table->date('expense_date')->nullable();
            $table->foreignId('branch_or_warehouse_id')->nullable()->constrained('branch_or_warehouses');
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
        Schema::dropIfExists('expenses');
    }
}
