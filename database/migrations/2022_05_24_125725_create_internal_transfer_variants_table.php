<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalTransferVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_transfer_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_transfer_id')->constrained();
            $table->foreignId('variant_id')->constrained();
            $table->integer('unit_quantity');
            $table->string('lot_reference_no');
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
        Schema::dropIfExists('internal_transfer_variants');
    }
}
