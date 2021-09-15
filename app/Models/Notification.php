<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Notification extends Model
{
    //
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'sys_notification';
    protected $fillable = [
        'type','title','message','action','sort_order','priority','ref_id','ref_type','sender','receiver','schedule_date','status','system_code','json'
    ];
}
