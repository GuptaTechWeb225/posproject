<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchOrWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_or_warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->nullable()->constrained('users', 'id');
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('status_id')->nullable()->constrained();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->enum('type', ['branch', 'warehouse']);
            $table->foreignId('tax_id')->nullable()->constrained('taxes')->onDelete('cascade');
            $table->unsignedBigInteger('tenant_id')->default(1);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('branch_or_warehouses');
    }
}
