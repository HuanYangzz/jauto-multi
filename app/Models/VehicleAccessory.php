<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class VehicleAccessory extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'vehicle_accessory';
    protected $fillable = [
        'item_code','description','uom','cost','recommended_selling_price','min_price','status','global_id','type','created_by','system_code','priority','no_of_stock','standalone','attached','balance'
    ];
}
