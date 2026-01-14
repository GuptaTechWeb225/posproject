<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_tax', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('taxes')->nullable();
            $table->foreignId('tax_id')->constrained();
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
        Schema::dropIfExists('tax_tax');
    }
}
