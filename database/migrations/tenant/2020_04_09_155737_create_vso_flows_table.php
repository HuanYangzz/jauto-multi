<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVsoFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vso_flow', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('vso_flow_id')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->integer('is_required')->nullable();
            $table->string('requirement')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('vso_flow');
    }
}
