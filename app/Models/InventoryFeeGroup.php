<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class InventoryFeeGroup extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'inventory_fee_group';
    protected $fillable = [
        'item_code','description','uom','cost','recommended_selling_price','min_price','status','system_code','created_by','type','code','priority'
    ];
}
