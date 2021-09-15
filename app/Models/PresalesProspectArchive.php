<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PresalesProspectArchive extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'presales_prospect_archive';
    protected $fillable = [
        'user_id','salesman_id', 'event_id','customer_id','name', 'contact', 'remarks','status','full_name','identity','company','email','website','job_title','contact_1','contact_2','address_1','address_2','created_by','company_reg_no','contact_home','contact_office','city','state','postcode','country','system_code','code','new_prospect_code'
    ];
}
