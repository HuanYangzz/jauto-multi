<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class FileAttachment extends Model
{
    use LogsActivity;
    use UsesTenantConnection;
	protected static $logFillable = true;
    //
    protected $table = 'sys_file_attachment';
    protected $fillable = [
        'ref_id', 'uploaded_by', 'path', 'filename','remark','type'
    ];
}
