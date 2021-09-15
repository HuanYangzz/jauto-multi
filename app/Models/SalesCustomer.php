<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class SalesCustomer extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'sales_customer';
    protected $fillable = [
        'name', 'contact', 'remarks','status','full_name','identity','company','email','website','job_title','contact_1','contact_2','address_1','address_2','created_by','company_reg_no','contact_home','contact_office','city','state','postcode','country','system_code','customer_type','address_json','pic_json','email_cc','salesman_id','verified','license_no','ic_verified','license_verified','ssm_verified','code'
    ];
}
