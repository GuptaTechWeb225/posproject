<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('branch_or_warehouse_id')->constrained('branch_or_warehouses');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('opened_by')->nullable()->constrained('users');
            $table->foreignId('closed_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->dateTime('opening_time')->nullable();
            $table->dateTime('closing_time')->nullable();
            $table->double('opening_balance')->default(0);
            $table->double('closing_balance')->default(0);
            $table->text('note')->nullable();
            $table->boolean('multiple_access')->default(1);
            $table->string('user_ids')->nullable();
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
        Schema::dropIfExists('cash_registers');
    }
}
