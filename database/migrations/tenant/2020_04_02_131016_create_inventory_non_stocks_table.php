<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryNonStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_non_stock', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('item_code')->nullable();
            $table->string('system_code')->nullable();
            $table->string('description')->nullable();
            $table->string('uom')->nullable();
            $table->float('cost')->nullable();
            $table->float('recommended_selling_price')->nullable();
            $table->float('min_price')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('code')->nullable();
            $table->integer('priority')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_non_stock');
    }
}
