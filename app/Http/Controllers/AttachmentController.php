<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\CompanyProfile;;
use App\Models\FileAttachment;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Auth;

class AttachmentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upload_attachment(Request $request,$type_name,$attr_name,$id)
    {
        $website   = app(\Hyn\Tenancy\Environment::class)->website();
        $uuid = $website->uuid;

        if(!Auth::user())
        {
            abort(403);
        }

        $auth_id = Auth::user()->id;

        $image = $request->input($attr_name);
        if($image)
        {
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            $target_dir = public_path()."/attachment/".$uuid."/".$type_name."/".$id."/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $ts = Carbon::now()->timestamp;
            $name = $ts."_".preg_replace("/[^a-zA-Z0-9.]/", "", $type_name);
            $name = $name.".png";

            $target_file = $target_dir . basename($name);
            $uploadOk = 1;

            \File::put($target_file, base64_decode($image));
            $file =  new FileAttachment();
            $file->path = '/attachment/'.$uuid."/".$type_name.'/'.$id."/".basename($name);
            $file->filename = basename($name);
            $file->uploaded_by = $auth_id;
            $file->ref_id = $id;
            $file->type = $attr_name;
            $file->save();

            if($file->type == "company_logo")
            {
                $company = CompanyProfile::first();
                if($company)
                {
                    $company->logo_id = $file->id;
                    $company->logo_url = $file->path;
                    $company->save();
                }
            }
        }

        return URL::previous();
    }

    public function upload_file(Request $request,$type_name,$attr_name,$id)
    {
        $website   = app(\Hyn\Tenancy\Environment::class)->website();
        $uuid = $website->uuid;

        if(!Auth::user())
        {
            abort(403);
        }

        $auth_id = Auth::user()->id;

        if($request->hasFile('file'))
        {
            $target_dir = public_path()."/attachment/".$uuid."/".$type_name."/".$id."/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $f = $request->file('file');

            $first = true;

            $ts = Carbon::now()->timestamp;
            $name     = urlencode($f->getClientOriginalName());
            $name = $ts."_".preg_replace("/[^a-zA-Z0-9.]/", "", $name);

            $tmpName = $name;
            $size = $f->getSize();
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $filename = strtolower(pathinfo($name, PATHINFO_FILENAME));

            $target_file = $target_dir . basename($name);
            
            $uploadOk = 1;

            if ($uploadOk == 0) {
                $message = $message?$message:"Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                if ($f->move($target_dir,$name)) {
                    $file =  new FileAttachment();
                   $file->path = '/attachment/'.$uuid."/".$type_name."/".$id."/".basename($name);
                   $file->filename = basename($name);
                   $file->uploaded_by = $auth_id;
                   $file->ref_id = $id;
                   $file->type = $type_name;
                   $file->save();
                }
            }
        }
        

        return URL::previous();
    }

    public function remove_pdf(Request $request,$id)
    {
        if(!Auth::user())
        {
            abort(403);
        }

        $auth_id = Auth::user()->id;
        $message = '';

        $a = FileAttachment::find($id);

        if($a)
        {
            $pid = $a->ref_id;
            $a->delete();

            return URL::previous();
        }
    }
}