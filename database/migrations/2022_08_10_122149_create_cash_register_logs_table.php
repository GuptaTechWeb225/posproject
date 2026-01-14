<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegisterLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_register_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('cash_register_id')->constrained('cash_registers');
            $table->foreignId('branch_or_warehouse_id')->constrained('branch_or_warehouses');
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->cascadeOnDelete();
            $table->double('opening_balance')->default(0);
            $table->double('closing_balance')->default(0);
            $table->double('cash_receives')->default(0);
            $table->double('cash_sales')->default(0);
            $table->double('difference')->default(0);
            $table->timestamp('opening_time');
            $table->timestamp('closing_time')->nullable();
            $table->foreignId('opened_by')->constrained('users');
            $table->foreignId('closed_by')->constrained('users');
            $table->foreignId('status_id')->constrained('statuses');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('cash_register_logs');
    }
}
