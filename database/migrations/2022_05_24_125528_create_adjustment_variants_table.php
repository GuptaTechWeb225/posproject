<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjustmentVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustment_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adjustment_id')->nullable()->constrained('adjustments');
            $table->foreignId('variant_id')->nullable()->constrained('variants');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->integer('unit_quantity')->default(1);
            $table->enum('adjustment_type',['addition', 'subtraction'] );
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
        Schema::dropIfExists('adjustment_variants');
    }
}