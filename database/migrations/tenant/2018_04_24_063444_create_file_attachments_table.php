<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_file_attachment', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('ref_id')->nullable();
            $table->string('uploaded_by')->nullable();
            $table->string('path')->nullable();
            $table->string('filename')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('sys_file_attachment');
    }
}
