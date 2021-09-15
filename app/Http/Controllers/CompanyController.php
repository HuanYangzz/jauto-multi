<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\CompanyProfile;
use App\Models\SysPostalCode;
use App\Models\CompanyBranch;
use App\Models\CompanyBranchEmployee;
use App\Models\CompanyEmployee;
use App\Models\User;
use App\Models\PersonInCharge;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Auth;

class CompanyController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function company_profile(Request $request){
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $message = $request->input('message');

        $item = CompanyProfile::first();
        if(!$item)
        {
            $item = new CompanyProfile();
            $item->save();
        }

        return view('company_module.company_profile',['item'=>$item,'message'=>$message]);
    }

    public function update_company(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $item = CompanyProfile::first();
        if(!$item)
        {
            abort(403);
        }

        $item->fill($request->all());
        $item->save();

        return redirect()->action('CompanyController@company_profile',['message'=>"Data Saved"]);
    }

    public function branch_list(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $message = $request->input('message');       
        $list = CompanyBranch::where('type','BRANCH')->get();

        return view('company_module.branch_list',['list'=>$list,'type'=>'BRANCH','message'=>$message]);
    }

    public function branch(Request $request,$code){
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $type = "BRANCH";
        $message = $request->input('message');

        $item = CompanyBranch::where('branch_code',$code)->first();
        if(!$item)
        {
            $item = new CompanyBranch();
        }
        $list = PersonInCharge::where([['ref_type',$type],['ref_id',$item->id]])->get();
        $employee_list = CompanyEmployee::where([['status','ACTIVE']])->get();
        foreach($employee_list as $e)
        {
            $e->contact_str = HelperFunction::convertContact($e->contact);
            if($list->where('user_id',$e->user_id)->first())
            {
                $e->class="highlight";
            }
        }

        return view('company_module.branch',['item'=>$item,'list'=>$list,'employee_list'=>$employee_list,'type'=>$type,'message'=>$message]);
    }

    public function broker_list(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $message = $request->input('message');       
        $list = CompanyBranch::where('type','BROKER')->get();

        return view('company_module.branch_list',['list'=>$list,'type'=>'BROKER','message'=>$message]);
    }

    public function broker(Request $request,$code){
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $type = "BROKER";
        $message = $request->input('message');

        $item = CompanyBranch::where('branch_code',$code)->first();
        if(!$item)
        {
            $item = new CompanyBranch();
        }
        $list = PersonInCharge::where([['ref_type',$type],['ref_id',$item->id]])->get();
        $employee_list = CompanyEmployee::where([['status','ACTIVE']])->get();
        foreach($employee_list as $e)
        {
            $e->contact_str = HelperFunction::convertContact($e->contact);
            if($list->where('user_id',$e->user_id)->first())
            {
                $e->class="highlight";
            }
        }

        return view('company_module.branch',['item'=>$item,'list'=>$list,'employee_list'=>$employee_list,'type'=>$type,'message'=>$message]);
    }

    public function update_branch(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->get('id');
        $type = $request->get('type');

        $item = CompanyBranch::find($id);
        if(!$item)
        {
            $item = new CompanyBranch();
            $year_prefix = date('y');
            $max = CompanyBranch::whereRaw('LENGTH(branch_code) = 8')->orderBy('id','desc')->first();
            if($max)
            {
                $branch_no = substr($max->branch_code,5,4) + 1;
                $code = "CB".$year_prefix.str_pad($branch_no,4,'0',STR_PAD_LEFT);
            }
            else
            {
                $code = "CB".$year_prefix."0001";
            }
            $item->branch_code = $code;
            $item->status="ACTIVE";
        }
        $item->fill($request->all());
        $item->save();

        if($type=="BRANCH")
        {
            return redirect()->action('CompanyController@branch',['code'=>$item->branch_code,'message'=>"Data Saved"]);
        }
        else
        {
            return redirect()->action('CompanyController@broker',['code'=>$item->branch_code,'message'=>"Data Saved"]);
        }
    }

    public function update_pic(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $branch_id = $request->get('branch_id');
        $pic_ids = $request->get('pic_ids');
        $type = $request->get('type');

        $branch = CompanyBranch::find($branch_id);
        if(!$branch)
        {
            abort(403);
        }

        PersonInCharge::where([['ref_type',$type],['ref_id',$branch->id]])->delete();

        foreach($pic_ids->explode(',') as $pic_id)
        {
            $employee = CompanyEmployee::find($pic_id);
            if(!$employee)
            {
                continue;
            }

            $pic = new PersonInCharge();
            $pic->type=$type;
            $pic->ref_type=$type;
            $pic->ref_id = $branch->id;
            $pic->employee_id = $pic_id;
            $pic->name = $employee->name;
            $pic->contact_1 = $employee->contact_1;
            $pic->contact_2 = $employee->contact_2;
            $pic->ext = $employee->ext;
            $pic->email = $employee->email;
            $pic->job_title = $empoyee->job_title;
            $pic->user_id  =$employee->user_id;
            $pic->save();
        }

        if($type=="BRANCH")
        {
            return redirect()->action('CompanyController@branch',['code'=>$item->branch_code,'message'=>"Data Saved"]);
        }
        else
        {
            return redirect()->action('CompanyController@broker',['code'=>$item->branch_code,'message'=>"Data Saved"]);
        }
    }

    public function employee_list(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $type = "EMPLOYEE";
        $message = $request->input('message');       
        $list = CompanyEmployee::where('employee_type',$type)->get();
        foreach($list as $item)
        {
            $item->contact_str = HelperFunction::convertContact($employee->item);
        }

        return view('company_module.employee_list',['list'=>$list,'type'=>$type,'message'=>$message]);
    }

    public function employee(Request $request,$code){
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $type = "EMPLOYEE";
        $message = $request->input('message');

        $item = CompanyEmployee::where('code',$code)->first();
        if(!$item)
        {
            $item = new CompanyEmployee();
        }

        $upline_list = [];
        $user_list = User::where('status','ACTIVE')->get();
        if(!$request->user()->hasRole(['System Admin']))
        {
            foreach($user_list as $user)
            {
                if(!$user->hasRole(['System Admin']))
                {
                    array_push($ids,$user->id);
                }
            }
            $user_list = User::whereIn('id',$ids)->get();
        }
        $branch_list = CompanyBranch::where("status","ACTIVE")->get();

        $managers = User::role(['Manager'])->get();
        foreach($managers as $m)
        {
            $employee = CompanyEmployee::where('user_id',$m->id)->first();
            if($employee && $employee->id!=$c->id)
            {
                array_push($upline_list,$employee);
                array_push($upids,$employee->id);
            }
        }

        foreach($branch_list as $branch)
        {
            $sb = CompanyBranchEmployee::where([['employee_id',$item->id],['branch_id',$branch->id]])->first();
            if($sb)
            {
                $branch->selected = true;
                $bms = PersonInCharge::where([['ref_id',$sb->branch_id],['ref_type','BRANCH']])->get();
                foreach($bms as $bm)
                {
                    $m = User::find($bm->user_id);
                    $employee = CompanyEmployee::where('user_id',$m->id)->whereNotIn('id',$upids)->first();
                    if($employee && $employee->id!=$c->id)
                    {
                        array_push($upline_list,$employee);
                    }
                }
            }
        }

        return view('company_module.employee',['item'=>$item,'type'=>$type,'branch_list'=>$branch_list,'user_list'=>$user_list,'upline_list'=>$upline_list,'message'=>$message]);
    }

    public function update_employee(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->get('id');
        $type = $request->get('type');

        $item = CompanyEmployee::find($id);
        if(!$item)
        {
            $item = new CompanyEmployee();
            $year_prefix = date('y');
            $max = CompanyEmployee::whereRaw('LENGTH(code) = 8')->orderBy('id','desc')->first();
            if($max)
            {
                $employee_no = substr($max->code,5,4) + 1;
                $code = "SP".$year_prefix.str_pad($employee_no,4,'0',STR_PAD_LEFT);
            }
            else
            {
                $code = "SP".$year_prefix."0001";
            }
            $item->code = $code;
            $item->status="ACTIVE";
        }
        $item->fill($request->all());
        if($item->left_at == "")
        {
            $item->left_at = null;
        }
        if($item->upline_id == "")
        {
            $item->upline_id = null;
        }
        $item->save();

        $branch_ids = $request->get('branch_ids');
        if($branch_ids && sizeof($branch_ids)>0)
        {
            $item->branch_id = 0;
            CompanyBranchEmployee::where('employee_id',$item->id)->whereNotIn('branch_id',$branch_ids)->delete();
            foreach($branch_ids as $branch_id)
            {
                if($item->branch_id == 0)
                {
                    $item->branch_id = $branch_id;
                }
                $ex = CompanyBranchEmployee::where([['branch_id',$branch_id],['employee_id',$item->id]])->first();
                if(!$ex)
                {
                    $ex = new CompanyBranchEmployee();
                    $ex->branch_id = $branch_id;
                    $ex->employee_id = $item->id;
                    $ex->save();
                }
            }
        }

        if($type=="EMPLOYEE")
        {
            return redirect()->action('CompanyController@employee',['code'=>$item->code,'message'=>"Data Saved"]);
        }
        else
        {
            return redirect()->action('CompanyController@salesman',['code'=>$item->code,'message'=>"Data Saved"]);
        }
    }

    public function deactivate_employee(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $id = $request->get('id');
        $description = $request->get('description');
        $employee = CompanyEmployee::find($id);
        if(!$employee)
        {
            abort(403);
        }
        $message = "\n".Carbon::today()->toFormattedDateString().": Deactivated by ".Auth::user()->name." due to [".$description."]";

        $employee->status = "INACTIVE";
        $employee->remarks = $employee->remarks."\n".$message;
        $employee->save();

        if($type=="EMPLOYEE")
        {
            return redirect()->action("CompanyController@employee",["message"=>"Data Saved","code"=>$employee->code]);
        }
        else
        {
            return redirect()->action("CompanyController@salesman",["message"=>"Data Saved","code"=>$employee->code]);
        }
    }
    
    public function salesman_list(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $type = "SALESMAN";
        $message = $request->input('message');       
        $list = CompanyEmployee::where('employee_type',$type)->get();
        foreach($list as $item)
        {
            $item->contact_str = HelperFunction::convertContact($item->contact);
        }

        return view('company_module.employee_list',['list'=>$list,'type'=>$type,'message'=>$message]);
    }

    public function salesman(Request $request,$code){
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $type = "SALESMAN";
        $message = $request->input('message');

        $item = CompanyEmployee::where('code',$code)->first();
        if(!$item)
        {
            $item = new CompanyEmployee();
        }

        $upline_list = [];
        $user_list = User::where('status','ACTIVE')->get();
        if(!$request->user()->hasRole(['System Admin']))
        {
            foreach($user_list as $user)
            {
                if(!$user->hasRole(['System Admin']))
                {
                    array_push($ids,$user->id);
                }
            }
            $user_list = User::whereIn('id',$ids)->get();
        }
        $branch_list = CompanyBranch::where("status","ACTIVE")->get();

        $upids = [];
        $managers = User::role(['Manager'])->get();
        foreach($managers as $m)
        {
            $employee = CompanyEmployee::where('user_id',$m->id)->first();
            if($employee && $employee->id!=$item->id)
            {
                array_push($upline_list,$employee);
                array_push($upids,$employee->id);
            }
        }

        foreach($branch_list as $branch)
        {
            $sb = CompanyBranchEmployee::where([['employee_id',$item->id],['branch_id',$branch->id]])->first();
            if($sb)
            {
                $branch->selected = true;
                $bms = PersonInCharge::where([['ref_id',$sb->branch_id],['ref_type','BRANCH']])->get();
                foreach($bms as $bm)
                {
                    $m = User::find($bm->user_id);
                    $employee = CompanyEmployee::where('user_id',$m->id)->whereNotIn('id',$upids)->first();
                    if($employee && $employee->id!=$c->id)
                    {
                        array_push($upline_list,$employee);
                    }
                }
            }
        }

        return view('company_module.employee',['item'=>$item,'type'=>$type,'branch_list'=>$branch_list,'user_list'=>$user_list,'upline_list'=>$upline_list,'message'=>$message]);
    }

    public function check_user(Request $request,$type,$id)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $employee_id = $request->get('employee_id');
        $user_id = $request->get('user_id');

        if($type=="USER")
        {
            $u = User::find($id);
            if(!$u)
            {
                return ["status"=>"Not Found"];
            }
            $employee = CompanyEmployee::where('user_id',$id)->first();
            if($employee && $employee->id != $employee_id)
            {
                return ["status"=>"Exists"];
            }

            return ["status"=>"OK","email"=>$u->email,"name"=>$u->name,'contact_prefix'=>$u->contact_prefix,'contact'=>$u->contact];
        }
        else if($type=='EMPLOYEE')
        {
            $u= CompanyEmployee::find($id);
            if(!$u)
            {
                return ["status"=>"Not Found"];
            }
            if($u->user_id>0 && $u->user_id != $user_id)
            {
                return ["status"=>"Exists"];
            }

            return ["status"=>"OK","email"=>$u->email,"name"=>$u->name,'contact'=>$u->contact];
        }
    }

    public function get_upline(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $ids = $request->get("branch_ids");
        $managers = User::role('Manager')->get();

        $list = [];
        foreach($managers as $m)
        {
            $employee = CompanyEmployee::where('user_id',$m->id)->first();
            if($employee)
            {
                array_push($list,$employee);
            }
        }
        
        $bms = PersonInCharge::where('ref_type','BRANCH')->whereIn('ref_id',$ids)->get();
        foreach($bms as $bm)
        {
            $m = CompanyEmployee::where('user_id',$bm->user_id)->first();
            if($m)
            {
                array_push($list,$m);
            }
        }

        return $list;
    }

    public function user_list(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $message = $request->input('message');

        $list =  User::all();
        $ids = [];
        if(!$request->user()->hasRole(['System Admin']) && !$request->user()->hasRole(['Manager']))
        {
            foreach($list as $user)
            {
                if(!$user->hasRole(['System Admin']) && !$user->hasRole(['Manager']))
                {
                    array_push($ids,$user->id);
                }
            }
            $list = User::whereIn('id',$ids)->get();
        }
        else if(!$request->user()->hasRole(['System Admin']))
        {
            foreach($list as $user)
            {
                if(!$user->hasRole(['System Admin']))
                {
                    array_push($ids,$user->id);
                }
            }
            $list = User::whereIn('id',$ids)->get();
        }
        foreach($list as $user)
        {
            $user->contact_str = HelperFunction::convertContact($user->contact);
        }

        return view('company_module.user_list',['list'=>$list,'message'=>$message]);
    }

    public function user(Request $request,$code){
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $user = User::where('code',$code)->first();
        if(!$user)
        {
            $user = new User();
        }

        if(Auth::user()->hasRole('System Admin'))
        {
            $roles = Role::all();
        }
        else if(Auth::user()->hasRole('Manager'))
        {
            $roles = Role::where('name','!=','System Admin')->get();    
        }
        else if(Auth::user()->hasRole('Admin'))
        {
            $roles = Role::where([['name','!=','System Admin'],['name','!=','Manager']])->get();   
        }
        else
        {
            abort(403);
        }

        foreach($roles as $role)
        {
            if($user->hasRole($role))
            {
                $role->selected = true;
            }
        }
        
        $message = $request->input('message');
        $message_type = $request->input('message_type');
        $message_type = $message_type?$message_type:'success';

        $permissions = Permission::all();

        $employees = CompanyEmployee::where('status','ACTIVE')->get();
        foreach($employees as $item)
        {
            $item->contact_str = HelperFunction::convertContact($item->contact);
        }

        $employee = CompanyEmployee::where('user_id',$user->id)->first();
        if($employee && $user->id>0)
        {
            $user->employee_id = $employee->id;
        }
        else
        {
            $user->employee_id = 0;
        }

        $branches = CompanyBranch::where('status','ACTIVE')->get();
        foreach($branches as $b)
        {
            $pic = PersonInCharge::where([['ref_type','BRANCH'],['ref_id',$b->id],['employee_id',$user->employee_id]])->first();
            if($pic)
            {
                $b->selected = true;
            }
        }

        if(url()->previous() == url('/user_list'))
        {
            $back_url = url("/user_list");    
        }
        else
        {
            $back_url = null;
        }
        
        return view("company_module.user",['item'=>$user,'role_list'=>$roles,'permission_list'=>$permissions,'message'=>$message,'message_type'=>$message_type,'back_url'=>$back_url,'branch_list'=>$branches,'employee_list'=>$employees]);
    }

    public function update_user(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $id = $request->get('id');
        $user = User::find($id);
        
        if(!$user)
        {
            $user = new User();
            $user->password = bcrypt('p@ssw0rd');
            $year_prefix = date('y');
            $max = User::whereRaw('LENGTH(code) = 8')->orderBy('id','desc')->first();
            if($max)
            {
                $user_no = substr($max->code,5,4) + 1;
                $code = "US".$year_prefix.str_pad($user_no,4,'0',STR_PAD_LEFT);
            }
            else
            {
                $code = "US".$year_prefix."0001";
            }
            $user->code = $code;   
        }

        $employee_id = $request->get('employee_id');
        $employee = CompanyEmployee::find($employee_id);
        if($employee)
        {
            $employee->user_id = $user->id;
            if($employee->contact == "")
            {
                $employee->contact = $user->contact;
            }
            if($employee->email == "")
            {
                $employee->email = $user->email;
            }
            $employee->save();
        }
        $user->fill($request->all());

        $role = $request->get('role_ids');
        $user->syncRoles($role);
        $user->save();

        $bids = request('branch_ids');
        if($bids!="" && $employee_id>0)
        {
            PersonInCharge::where([['ref_type','BRANCH'],['employee_id',$employee_id]])->whereNotIn('ref_id',$bids)->delete();
            foreach($bids as $bid)
            {
                $pic = PersonInCharge::where([['ref_type','BRANCH'],['ref_id',$bid],['employee_id',$employee_id]])->first();
                if(!$pic)
                {
                    $pic = new PersonInCharge();
                    $pic->ref_type = "BRANCH";
                    $pic->ref_id = $bid;
                    $pic->employee_id = $employee_id;
                    $pic->full_name = $user->name;
                    $pic->name = $user->name;
                    $pic->email = $user->email;
                    $pic->contact_1 = $user->contact;
                    $pic->save();
                }
            }
            $role = Role::where('name','Branch Manager')->first();
            if(!$role)
            {
                $role = new Role();
                $role->name = "Branch Manager";
                $role->save();
            }
            if(PersonInCharge::where([['ref_type','BRANCH'],['employee_id',$employee_id]])->count()>0)
            {
                if(!$user->hasRole($role))
                {
                    $user->roles()->attach($role);
                }
            }
            else
            {
                if($user->hasRole($role))
                {
                    $user->roles()->detach($role);
                }
            }
        }

        return redirect()->action("CompanyController@user",["message"=>"Data Saved","code"=>$user->code]);
    }

    public function deactivate_user(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $id = $request->get('id');
        $description = $request->get('description');
        $user = User::find($id);
        if(!$user)
        {
            abort(403);
        }
        $message = "\n".Carbon::today()->toFormattedDateString().": Deactivated by ".Auth::user()->name." due to [".$description."]";

        $user->status = "INACTIVE";
        $user->remark = $user->remark."\n".$message;
        $user->save();

        return redirect()->action("CompanyController@user",["message"=>"Data Saved","code"=>$user->code]);
    }
}