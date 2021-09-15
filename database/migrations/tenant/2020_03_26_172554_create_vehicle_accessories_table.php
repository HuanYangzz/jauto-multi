<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_accessory', function (Blueprint $table) {
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
            $table->integer('global_id')->nullable();
            $table->string('type')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('code')->nullable();
            $table->integer('priority')->nullable();
            $table->integer('no_of_stock')->nullable();
            $table->integer('attached')->nullable();
            $table->integer('standalone')->nullable();
            $table->integer('balance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_accessory');
    }
}
