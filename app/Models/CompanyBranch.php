<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class CompanyBranch extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'company_branch';
    protected $fillable = [
        'branch_code','name','phone_1','phone_2','fax_no','email','email_cc','website','mail_address_1','mail_address_2','mail_city','mail_state','mail_postcode','mail_country','legal_address_1','legal_address_2','legal_city','legal_state','legal_postcode','legal_country','facebook','person_in_charge','system_code','type','remarks','status','company_reg_no'
    ];
}
