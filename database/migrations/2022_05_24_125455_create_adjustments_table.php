<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_or_warehouse_id')->nullable()->constrained('branch_or_warehouses');
            $table->foreignId('status_id')->nullable()->constrained('statuses');
            $table->date('adjustment_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
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
        Schema::dropIfExists('adjustments');
    }
}