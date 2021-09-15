<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_model', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('description')->nullable();
            $table->integer('global_id')->nullable();
            $table->string('status')->nullable();
            $table->integer('is_commercial')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_model');
    }
}
