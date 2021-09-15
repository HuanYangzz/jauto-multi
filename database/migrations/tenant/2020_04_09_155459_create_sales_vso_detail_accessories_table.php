<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesVsoDetailAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_vso_detail_accessory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('vso_id')->nullable();
            $table->integer('ref_id')->nullable();
            $table->string('ref_type')->nullable();
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->string('item_code')->nullable();
            $table->string('status')->nullable();
            $table->float('price')->nullable();
            $table->float('default_price')->nullable();
            $table->string('system_code')->nullable();
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
        Schema::dropIfExists('sales_vso_detail_accessory');
    }
}
