<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_branch', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('system_code')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('email')->nullable();
            $table->string('email_cc')->nullable();
            $table->string('type')->nullable();
            $table->string('website')->nullable();
            $table->string('mail_address_1')->nullable();
            $table->string('mail_address_2')->nullable();
            $table->string('mail_city')->nullable();
            $table->string('mail_state')->nullable();
            $table->string('mail_postcode')->nullable();
            $table->string('mail_country')->nullable();
            $table->string('legal_address_1')->nullable();
            $table->string('legal_address_2')->nullable();
            $table->string('legal_city')->nullable();
            $table->string('legal_state')->nullable();
            $table->string('legal_postcode')->nullable();
            $table->string('legal_country')->nullable();
            $table->string('facebook')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable();
            $table->string("company_reg_no")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_branch');
    }
}
