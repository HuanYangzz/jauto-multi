<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class SalesVso extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'sales_vso';
    protected $fillable = [
        'prospect_id', 'salesman_id','vso_no','date_of_birth', 'full_name','contact','identity','address','status','remark','event_id','vso_date','system_code','customer_id','company_id','vso_type','company_reg_no','company_name','address_1','address_2','city','state','postcode','country','created_by','net_selling_price','otr_price','total_price','discount','trade_in','trade_in_detail','deposit','down_payment','balance_of_loan','cn_customer','ic_upload','license_upload','fleet_support','special_car_plate','cash_payment','submit_by','verify_by','approve_by','loan_accept','vdo_generate','vdo_sign','ssm_upload','contact_person','pic_json','upload_body_plan','upload_body_other','upload_inspection_0','upload_inspection_1','upload_inspection_2','upload_inspection_3','upload_inspection_4','upload_inspection_other','upload_k1','upload_com','upload_ehak_milik','upload_doc_other','upload_reg_insurance','upload_bookingno','upload_reg_other','upload_geran','upload_roadtax','upload_return_other','registration_review_json','upload_loan_agreement','purchase_price','branch_id','license_no','carplate','registration_date','draft_json','step_id'
    ];
}
