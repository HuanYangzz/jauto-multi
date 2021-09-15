<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_profile', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name')->nullable();
            $table->string('reg_no')->nullable();
            $table->string('license_no')->nullable();
            $table->integer('logo_id')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('email')->nullable();
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
            $table->string('person_in_charge')->nullable();
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
        Schema::dropIfExists('company_profile');
    }
}
