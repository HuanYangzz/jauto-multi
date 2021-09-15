<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class InventoryFee extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'inventory_fee';
    protected $fillable = [
        'vehicle_id','variant_id','item_code','description','price_1','price_2','price_3','status','type','priority','cost'
    ];
}
