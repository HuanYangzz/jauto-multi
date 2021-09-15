<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class SalesVsoDetail extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'sales_vso_detail';
    protected $fillable = [
        'vso_id','vehicle_id','variant_id','status','remark','price','default_price','stock_id','variant','color','capacity','make','transmission','body_type','drive_system','brand','model'  
    ];
}
