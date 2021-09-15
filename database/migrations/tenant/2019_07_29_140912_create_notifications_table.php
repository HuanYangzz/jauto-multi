<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_notification', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('type');
            $table->string('title');
            $table->string('message');
            $table->string('action');
            $table->integer('sort_order');
            $table->integer('priority');
            $table->integer('ref_id');
            $table->string('ref_type');
            $table->integer('sender');
            $table->integer('receiver');
            $table->datetime('schedule_date');
            $table->string('status');
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
        Schema::dropIfExists('sys_notification');
    }
}
