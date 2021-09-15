<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresalesProspectDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presales_prospect_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('user_id')->nullable();
            $table->integer('vehicle_id')->nullable();
            $table->integer('variant_id')->nullable();
            $table->double('price',10,2)->nullable();
            $table->integer('prospect_id')->nullable();
            $table->integer('prospect_history_id')->nullable();
            $table->string('remark')->nullable();
            $table->string('status')->nullable();
            $table->integer('salesman_id')->nullable();
            $table->string('transmission')->nullable();
            $table->string('body_type')->nullable();
            $table->string('color')->nullable();
            $table->integer('capacity')->nullable();
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
        Schema::dropIfExists('presales_prospect_detail');
    }
}
