<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lot_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->nullable()->constrained('lots');
            $table->foreignId('variant_id')->nullable()->constrained('variants');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->integer('unit_quantity')->default(1);
            $table->integer('unit_price')->default(0);
            $table->integer('total_unit_price')->default(0);
            $table->integer('unit_tax_percentage')->default(0);
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
        Schema::dropIfExists('lot_variants');
    }
}
