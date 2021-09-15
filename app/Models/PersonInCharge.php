<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PersonInCharge extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'person_in_charge';
    protected $fillable = [
        'type', 'name', 'contact', 'remarks', 'status', 'full_name', 'identity', 'company', 'email', 'website', 'job_title', 
        'contact_1', 'contact_2', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country','ref_type','ref_id','ext','employee_id'
    ];
}
