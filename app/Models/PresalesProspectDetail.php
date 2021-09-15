<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PresalesProspectDetail extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'presales_prospect_detail';
    protected $fillable = [
        'prospect_history_id','prospect_id','vehicle_id','variant_id','status','remark','salesman_id','color','make','capacity','transmission','body_type'
    ];
}
