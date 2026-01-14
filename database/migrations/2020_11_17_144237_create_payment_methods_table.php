<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias');
            $table->tinyInteger('is_default');
            $table->tinyInteger('is_for_client');
            $table->enum('rounded_to',['none','nearest_integer','nearest_half'])->default('none');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('status_id')->constrained('statuses');
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
        Schema::dropIfExists('payment_methods');
    }
}
