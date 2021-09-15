<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_variant', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('system_code')->nullable();
            $table->string('variant')->nullable();
            $table->string('description')->nullable();
            $table->string('color')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('make')->nullable();
            $table->integer('vehicle_id')->nullable();
            $table->float('price')->nullable();
            $table->float('min_price')->nullable();
            $table->string('status')->nullable();
            $table->string('transmission')->nullable();
            $table->string('body_type')->nullable();
            $table->string('drive_system')->nullable();
            $table->integer('global_id')->nullable();
            $table->string('code')->nullable();
            $table->string('vehicle_code')->nullable();
            $table->float('cost')->nullable();
            $table->string('type')->nullable();
            $table->string('CKD')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_variant');
    }
}
