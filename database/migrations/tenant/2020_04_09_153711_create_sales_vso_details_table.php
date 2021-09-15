<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesVsoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_vso_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('vehicle_id')->nullable();
            $table->integer('variant_id')->nullable();
            $table->double('price',10,2)->nullable();
            $table->double('default_price',10,2)->nullable();
            $table->integer('vso_id')->nullable();
            $table->string('remark')->nullable();
            $table->string('status')->nullable();
            $table->string('variant')->nullable();
            $table->string('color')->nullable();
            $table->string('transmission')->nullable();
            $table->string('body_type')->nullable();
            $table->string('drive_system')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('make')->nullable();
            $table->string('system_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_vso_detail');
    }
}
