<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVsosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_vso', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('system_code')->nullable();
            $table->string('full_name')->nullable();
            $table->string('address')->nullable();
            $table->string('remark')->nullable();
            $table->string('status')->nullable();
            $table->string('contact')->nullable();
            $table->string('identity')->nullable();
            $table->integer('salesman_id')->nullable();
            $table->integer('prospect_id')->nullable();
            $table->integer('event_id')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('vso_date')->nullable();
            $table->string('vso_no')->nullable();
            $table->float('net_selling_price')->nullable();
            $table->float('otr_price')->nullable();
            $table->float('total_price')->nullable();
            $table->float('discount')->nullable();
            $table->float('trade_in')->nullable();
            $table->float('down_payment')->nullable();
            $table->float('balance_of_loan')->nullable();
            $table->float('cn_customer')->nullable();
            $table->text('trade_in_detail')->nullable();
            $table->integer('ic_upload')->nullable();
            $table->integer('license_upload')->nullable();
            $table->string('fleet_support')->nullable();
            $table->string('special_car_plate')->nullable();
            $table->string('cash_payment')->nullable();
            $table->integer('submit_by')->nullable();
            $table->integer('verify_by')->nullable();
            $table->integer('approve_by')->nullable();
            $table->float('loan_accept')->nullable();
            $table->integer('upload_loan_agreement')->nullable();
            $table->string('vdo_generate')->nullable();
            $table->string('vdo_sign')->nullable();
            $table->integer('ssm_upload')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('pic_json')->nullable();
            $table->integer('upload_body_plan')->nullable();
            $table->integer('upload_body_other')->nullable();
            $table->integer('upload_inspection_0')->nullable();
            $table->integer('upload_inspection_1')->nullable();
            $table->integer('upload_inspection_2')->nullable();
            $table->integer('upload_inspection_3')->nullable();
            $table->integer('upload_inspection_4')->nullable();
            $table->integer('upload_inspection_other')->nullable();
            $table->integer('upload_k1')->nullable();
            $table->integer('upload_com')->nullable();
            $table->integer('upload_ehak_milik')->nullable();
            $table->integer('upload_doc_other')->nullable();
            $table->integer('upload_reg_insurance')->nullable();
            $table->integer('upload_bookingno')->nullable();
            $table->integer('upload_reg_other')->nullable();
            $table->integer('upload_geran')->nullable();
            $table->integer('upload_roadtax')->nullable();
            $table->integer('upload_return_other')->nullable();
            $table->text('registration_review_json')->nullable();
            $table->float('purchase_price')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('company_reg_no')->nullable();
            $table->string('company_name')->nullable();
            $table->string('vso_type')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->integer('created_by')->nullable();
            $table->float('deposit')->nullable();
            $table->string('license_no')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('carplate')->nullable();
            $table->date('registration_date')->nullable();
            $table->text('draft_json')->nullable();
            $table->integer('step_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_vso');
    }
}
