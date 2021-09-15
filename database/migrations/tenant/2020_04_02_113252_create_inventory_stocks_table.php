<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_stock', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('system_code');
            $table->integer('dvo_item_id');
            $table->integer('dvo_id');
            $table->integer('vso_id');
            $table->integer('vso_detail_id');
            $table->integer('vehicle_id');
            $table->integer('variant_id');
            $table->string('status');
            $table->string('engine_no');
            $table->string('chassis_no');
            $table->string('remark');
            $table->double('cost',10,2);
            $table->string('color');
            $table->integer('created_by');
            $table->string('code');
            $table->integer('branch_id');
            $table->string('carplate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_stock');
    }
}
