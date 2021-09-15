<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Models\SysPostalCode;
use Illuminate\Support\Facades\Input;
use function Sodium\add;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\FormulaHelper;
use Auth;
use DB;
use Excel;

class AdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function get_notification(Request $request)
    {
        $balance = [];
        $all = Notification::where([['receiver',Auth::id()],['status','NEW']])->orderBy('schedule_date','desc')->orderBy('status','desc')->orderBy('priority','desc');
        $count = $all->count();
        $unread = $all->take(5)->get();
        if($unread->count()<5)
        {
            $bal = 5 - $unread->count();
            $balance = Notification::where([['receiver',Auth::id()],['status','!=','NEW']])->orderBy('schedule_date','desc')->orderBy('status','desc')->orderBy('priority','desc')->take($bal)->get();
        }

        foreach([$unread,$balance] as $list)
        {
            foreach($list as $m)
            {
                $vdate = Carbon::createFromFormat('Y-m-d H:i:s',$m->schedule_date);
                $m->diff = $vdate->diffForHumans();

                if($m->action!="")
                {
                    $m->action_link = url($m->action);
                }
                else
                {
                    $m->action_link = "#";
                }
            }
        }

        return ['unread'=>$unread,'read'=>$balance,'count'=>$count];
    }

    public function read_notification(Request $request,$id)
    {
        $noti = Notification::find($id);
        if($noti)
        {
            if(Auth::Id()==$noti->receiver)
            {
                $noti->status="READ";
                $noti->save();
            }
        }
    }

    public function profile(Request $request)
    {
        $item = Auth::user();
        $message = $request->get('message');
        
        return view("global.profile",['item'=>$item,'message'=>$message]);
    }

    public function update_profile(Request $request)
    {
        $item = Auth::user();
        $item->name = $request->get('name');
        $item->save();

        $theme = $request->get('theme');

        return redirect()->action('AdminController@profile')->cookie(
            'theme', $theme, 525600
        );
    }

    public function update_password(Request $request,$user=null)
    {
        $item = Auth::user();
        if(!$user)
        {
            $item = $user;
        }
        if(!Hash::check(request('current_password'),$item->password))
        {
            return abort("403");
        }

        if($request->get('new_password')!=$request->get('confirm_password'))
        {
            return about('403');
        }
        $item->password = bcrypt($request->get('new_password'));
        $item->save();

        return redirect()->action('AdminController@profile',['message'=>'Data Saved']);
    }

    public function check_password(Request $request,$user=null)
    {
        $item = Auth::user();

        if(!$user)
        {
            $item = $user;
        }

        if(!Hash::check(request('current_password'),$item->password))
        {
            return "WRONG";
        }

        return "CORRECT";
    }

    public function check_zip(Request $request, $zip)
    {
        $address = SysPostalCode::where('zip',$zip)->first();

        return $address;
    }
}