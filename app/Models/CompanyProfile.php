<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class CompanyProfile extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'company_profile';
    protected $fillable = [
        'name','reg_no','license_no','logo_id','logo_url','phone_1','phone_2','fax_no','email','website','mail_address_1','mail_address_2','mail_city','mail_state','mail_postcode','mail_country','legal_address_1','legal_address_2','legal_city','legal_state','legal_postcode','legal_country','facebook','person_in_charge','system_code'
    ];
}
