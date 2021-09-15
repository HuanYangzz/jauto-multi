<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleModelAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_model_accessory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('vehicle_id')->nullable();
            $table->integer('vehicle_variant_id')->nullable();
            $table->integer('accessory_id')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_model_accessory');
    }
}
