<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class VehicleVariant extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'vehicle_variant';
    protected $fillable = [
        'variant', 'description', 'vehicle_id','color','capacity','make','price','min_price','transmission','body_type','global_id','status','code','vehicle_code','drive_system','system_code','cost','CKD','type'
    ];
}
