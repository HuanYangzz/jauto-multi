<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class InventoryStock extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'inventory_stock';
    protected $fillable = [
        'dvo_item_id','dvo_id','vehicle_id','variant_id','status','remark','chassis_no','vso_id','cost','vso_detail_id','engine_no','system_code','color','created_by','code','branch_id','carplate'
    ];
}
