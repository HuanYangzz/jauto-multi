<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_employee', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('system_code')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('event_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('name')->nullable();
            $table->string('contact')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable();
            $table->string('full_name')->nullable();
            $table->string('identity')->nullable();
            $table->string('company')->nullable();
            $table->string('company_reg_no')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('job_title')->nullable();
            $table->string('contact_1')->nullable();
            $table->string('contact_2')->nullable();
            $table->string('contact_home')->nullable();
            $table->string('contact_office')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->integer('created_by')->nullable();
            $table->date('join_at')->nullable();
            $table->date('left_at')->nullable();
            $table->string('code')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('upline_id')->nullable();
            $table->string('ext')->nullable();
            $table->string('employee_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_employee');
    }
}
