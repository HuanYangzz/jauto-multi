<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class VehicleModelAccessory extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'vehicle_model_accessory';
    protected $fillable = [
        'vehicle_id','vehicle_variant_id','accessory_id','status','type'
    ];
}
