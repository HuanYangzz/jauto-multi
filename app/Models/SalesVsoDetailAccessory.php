<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class SalesVsoDetailAccessory extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'sales_vso_detail_accessory';
    protected $fillable = [
        'vso_id','ref_id','type','description','price','default_price','status','ref_type','item_code'
    ];
}
