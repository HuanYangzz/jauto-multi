<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_customer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('system_code')->nullable();
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
            $table->string('customer_type')->nullable();
            $table->text('address_json')->nullable();
            $table->text('pic_json')->nullable();
            $table->string('email_cc')->nullable();
            $table->integer('salesman_id')->nullable();
            $table->string('code')->nullable();
            $table->boolean('verified')->default(false);
            $table->string('license_no')->nullable();
            $table->boolean('ic_verified')->default(false);
            $table->boolean('license_verified')->default(false);
            $table->boolean('ssm_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_customer');
    }
}
