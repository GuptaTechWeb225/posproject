<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('tax_id')->nullable()->constrained('taxes')->onDelete('cascade');
            $table->integer('stock_reminder_quantity')->nullable();
            $table->string('upc')->nullable();
            $table->float('selling_price')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('status_id')->nullable()->constrained('statuses')->onDelete('cascade');
            $table->unsignedBigInteger('tenant_id')->default(1);
            $table->string('name')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('variants');
    }
}
