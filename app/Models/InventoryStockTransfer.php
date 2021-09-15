<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class InventoryStockTransfer extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'inventory_stock_transfer';
    protected $fillable = [
        'transfer_date','stock_id','branch_from','branch_to','status','code','created_by','branch_from_str','branch_to_str'
    ];
}
