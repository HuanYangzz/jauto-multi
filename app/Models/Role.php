<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Role extends Model
{
	use LogsActivity;
	use UsesTenantConnection;
	protected static $logFillable = true;
    //
    public function users()
	{
	  return $this->belongsToMany(User::class);
	}
}
