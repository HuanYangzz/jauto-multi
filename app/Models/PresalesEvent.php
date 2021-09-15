<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PresalesEvent extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'presales_event';
    protected $fillable = [
        'name', 'display_name', 'location','start_at','end_at','status','system_code','remarks'
    ];
}
