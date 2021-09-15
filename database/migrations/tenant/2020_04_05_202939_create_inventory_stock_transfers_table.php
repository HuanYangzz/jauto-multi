<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_stock_transfer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->date('transfer_date')->nullable();
            $table->integer('stock_id')->nullable();
            $table->integer('branch_from')->nullable();
            $table->integer('branch_to')->nullable();
            $table->string('status')->nullable();
            $table->string('code')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('branch_from_str')->nullable();
            $table->string('branch_to_str')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_stock_transfer');
    }
}
