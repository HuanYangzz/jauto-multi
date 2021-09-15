<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_fee', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('vehicle_id')->nullable();
            $table->integer('variant_id')->nullable();
            $table->string('item_code')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->nullable();
            $table->float('price_1')->nullable();
            $table->float('price_2')->nullable();
            $table->float('price_3')->nullable();
            $table->string('type')->nullable();
            $table->integer('priority')->nullable();
            $table->float('cost')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_fee');
    }
}
