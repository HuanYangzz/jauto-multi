<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('system_code')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('contact')->unique();;
            $table->string('remark')->nullable();
            $table->string('code')->nullable();
            $table->boolean('active')->default(true);
            $table->string('status')->default("ACTIVE");
            $table->date('join_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->text('session_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user');
    }
}
