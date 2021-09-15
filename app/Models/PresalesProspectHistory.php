<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PresalesProspectHistory extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'presales_prospect_history';
    protected $fillable = [
        'prospect_id','event_id','status','remark','salesman_id','user_id','system_code','code'
    ];
}
