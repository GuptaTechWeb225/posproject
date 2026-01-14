<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->nullable()->constrained();
            $table->integer('branch_or_warehouse_from_id')->nullable();
            $table->integer('branch_or_warehouse_to_id')->nullable();
            $table->integer('total_transfer_cost')->nullable()->default(0);
            $table->date('date');
            $table->string('note')->nullable();
            $table->string('reference_no')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->boolean('adjusted_with_stock')->default(false)->comment('value will be true or false');
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
        Schema::dropIfExists('internal_transfers');
    }
}
