<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DateTime;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use App\Models\SalesCustomer;
use App\Models\VehicleModel;
use App\Models\VehicleVariant;
use App\Models\CompanyProfile;
use App\Models\CompanyEmployee;
use App\Models\User;
use App\Models\FileAttachment;
use App\Models\SalesVso;
use App\Models\SalesVsoDetail;
use App\Models\PresalesProspect;

class SalesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return CANCELLED
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('home');
    }

    public function customer_book(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman']))
        {
            abort(403);
        }
        if($request->user()->hasRole(['Manager','Admin','System Admin']))
        {
            $list = SalesCustomer::all();    
        }
        else
        {
            $list = SalesCustomer::where('created_by',Auth::id())->get();
        }
        
        $id = $request->get('id');
        $message = $request->get('message');

        foreach($list as $p)
        {
            if($p->customer_type=="COMPANY")
            {
                $p->customer = $p->company;
            }
            else
            {
                $p->customer = $p->full_name;
            }

            $tmp = $p->contact!=''?$p->contact:$p->contact_1;
            $p->contact_str = HelperFunction::convertContact($tmp);
            $who = "";

            $salesman = CompanyEmployee::find($p->salesman_id);
            if(!$salesman)
            {
                $created_by = User::find($p->created_by);
                if($created_by)
                {
                    $who = $created_by->name;
                    $salesman = CompanyEmployee::where('user_id',$created_by->id)->first();
                }
            }
            if($salesman)
            {
                $who = $salesman->name;
            }
                
            $p->created_by_str = $who;
        }

        return view('/sales_module/customer_book',['list'=>$list,'id'=>$id,'message'=>$message]);
    }

    public function customer_book_detail(Request $request,$code)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman']))
        {
            abort(403);
        }
        $back_url = url('/customer_book/');
        $page_title = "";
        $message = $request->get('message');

        $salesmen = CompanyEmployee::whereIn('employee_type',['Salesman','BROKER'])->where([['status','ACTIVE']])->get();
        $user_list =  CompanyEmployee::whereIn('employee_type',['Salesman','BROKER'])->where([['status','ACTIVE']])->get();
        $ids = [];
        if(!$request->user()->hasRole(['System Admin']))
        {
            foreach($user_list as $user)
            {
                array_push($ids,$user->id);
            }
            $salesmen = CompanyEmployee::whereIn('id',$ids)->get();
        }

        $c = SalesCustomer::where('code',$code)->first();
        if(!$c)
        {
            $type = $request->get('type');
            return view('/sales_module/customer_book_detail',["salesmen"=>$salesmen,"customer"=>$c,'message'=>$message,'customer_type'=>$type,'back_url'=>$back_url,'page_title'=>$page_title,'list'=>[]]);
        }

        $c->created_at_str = $c->created_at->format('Y-m-d');
        $c->created_by_user = User::find($c->created_by)->name;
        
        if($c->address_json != "")
        {
            $address_list = json_decode($c->address_json);
            foreach($address_list as $address)
            {
                if(isset($address->is_default) && $address->is_default == 1)
                {
                    $c->branch = $address->branch;
                }
                else
                {
                    $c->branch = "";
                }
            }
        }

        $list = SalesVso::where('customer_id',$c->id)->get();
        foreach($list as $p)
        {
            $p->contact_str = HelperFunction::convertContact($p->contact);
            
            $p->details = SalesVsoDetail::where('vso_id',$p->id)->first();
            if($p->details)
            {
                $variant = VehicleVariant::find($p->details->variant_id);
                if($variant)
                {
                    $vehicle = VehicleModel::find($variant->vehicle_id);
                    $p->vehicle = $vehicle->brand.' '.$vehicle->model.' '.$variant->variant;
                    $p->color = $p->details->color;
                }
            }

            if($p->salesman_id>0)
            {
                $dealer = CompanyEmployee::find($p->salesman_id);
                if($dealer)
                {
                    $p->dealer = $dealer->name;
                    $p->dealer_contact = HelperFunction::convertContact($dealer->contact);
                }
            }

            $day = Carbon::createFromFormat('Y-m-d',$p->vso_date)->startOfDay();
            if($day->diffInDays(Carbon::today())>0)
            {
                $p->duration = $day->diffForHumans();
            }
            else
            {
                $p->duration = "Today";
            }
        }

        return view('/sales_module/customer_book_detail',["salesmen"=>$salesmen,"item"=>$c,'message'=>$message,'back_url'=>$back_url,'page_title'=>$page_title,'history'=>$list]);
    }

    public function save_customer_data(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $c = SalesCustomer::where('code',$request->get('code'))->first();
        if(!$c)
        {
            $c = new SalesCustomer();
            $c->created_by = Auth::id();
            $c->status = 'ACTIVE';
            $year_prefix = date('y');
            $max = SalesCustomer::whereRaw('LENGTH(code) = 8')->orderBy('id','desc')->first();
            if($max)
            {
                $max_no = substr($max->code,5,4) + 1;
                $code = "CS".$year_prefix.str_pad($max_no,4,'0',STR_PAD_LEFT);
            }
            else
            {
                $code = "CS".$year_prefix."0001";
            }
            $c->code = $code;
            $c->save();
        }
        $c->name = $request->get('name');
        $c->contact = $request->get('contact');
        $c->remarks = $request->get('remarks');
        $c->full_name = $request->get('full_name');
        $c->identity = $request->get('identity');
        $c->customer_type = $request->get('customer_type');
        
        if($c->customer_type=='COMPANY' && $request->get('company')=="")
        {
            $c->company = $request->get('full_name');
        }
        else
        {
            $c->company = $request->get('company');
        }
        $c->company_reg_no = $request->get('company_reg_no');
        $c->email = $request->get('email');
        $c->website = $request->get('website');
        $c->email_cc = $request->get('email_cc');
        $c->job_title = $request->get('job_title');
        $c->contact_1 = $request->get('contact_1');
        $c->salesman_id = $request->get('salesman_id')!=""?$request->get('salesman_id'):0;
        $c->pic_json = $request->get('pic_json');
        $c->address_json = trim($request->get('address_json'));
        if($c->address_json != "")
        {
            $address_list = json_decode($c->address_json);
            foreach($address_list as $address)
            {
                if(isset($address->is_default) && $address->is_default == 1)
                {
                    $c->address_1 = $address->address_1;
                    $c->address_2 = $address->address_2;
                    $c->postcode = $address->zip;
                    $c->city = $address->city;
                    $c->state = $address->state;
                    $c->country = $address->country;
                }
            }
        }

        $c->save();

        return redirect()->action('SalesController@customer_book_detail',['code'=>$c->code]);
    }

    public function vso_form(Request $request,$code=null)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman','demo']))
        {
            abort(403);
        }

        $vehicle = VehicleModel::all();
        $variant = VehicleVariant::all();
        $brand = VehicleModel::select('brand')->distinct()->get();
        $pid = null;
        $vso = null;
        $details = null;

        $vso = SalesVso::where('vso_no',$code)->first();
        if($vso)
        {
            $pid = $vso->id;
            $detail = SalesVsoDetail::where('vso_id',$pid)->first();
            if($detail)
            {
                $vso->vehicle_id = $detail->vehicle_id;
                $vso->variant_id = $detail->variant_id;
                $vso->color = $detail->color;
            }

            if($vso->vehicle_id>0)
            {
                $m = VehicleModel::find($vso->vehicle_id);
                $vso->model = $m->brand."   ".$m->model;
                $variant = VehicleVariant::where('vehicle_id',$m->id)->get();
            }
            if($vso->variant_id>0)
            {
                $v = VehicleVariant::find($vso->variant_id);
                $vso->variant = $v->variant;
            }
            
            $files = [];
            $pdf = FileAttachment::where('type','vso')->where('ref_id',$pid)->orderBy('id','desc')->get();
            foreach($pdf as $f)
            {
                if(strpos($f->filename,'.pdf'))
                {
                    array_push($files, ["image"=>"/img/pdf.png","id"=>$f->id,"file"=>$f->path,"type"=>"pdf"]);
                }
                else
                {
                    array_push($files, ["image"=>$f->path,"id"=>$f->id,"file"=>$f->path,"type"=>"image"]);
                }
            }
            $vso->files = $files;
        }

        $type = $request->get('type');
        $office_no="";
        $salesman = CompanyEmployee::where('user_id',Auth::Id())->first();
        if($salesman)
        {
            $upline = CompanyEmployee::find($salesman->upline_id);
            if($upline)
            {
                $office_no = $upline->contact;
                $office_name = $upline->full_name;
            }
            else
            {
                $branch = CompanyBranch::find($salesman->branch_id);
                if($branch)
                {
                    $office_no = $branch->phone_1;
                    $office_name = $branch->name;
                }
            }
        }

        if($office_no == "")
        {
            $company = CompanyProfile::first();
            $office_no = $company?$company->phone_1:'';
            $office_name = $company->name;
        }
        $office_no = HelperFunction::convertContact($office_no);

        return view('sales_module.vso_form',['variant'=>$variant,'vehicle'=>$vehicle,'brand'=>$brand,'vso'=>$vso,'details'=>$details,'pid'=>$pid,'type'=>$type,'office_no'=>$office_no,'office_name'=>$office_name]);
    }

    public function review(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman']))
        {
            abort(403);
        }
        $user = Auth::user();
        $current_salesman = CompanyEmployee::where('user_id',$user->id)->first();
        $month_ago = new Carbon('first day of this month');
        $year_ago = new Carbon('01/01/'.date('Y'));
        $text = "Generated by ";

        if($current_salesman)
        {
            $text .= $current_salesman->full_name;
            $total_prospect = PresalesProspect::where('salesman_id',$current_salesman->id)->whereDate('created_at','>=',$year_ago)->count();
            $new_prospect = PresalesProspect::where('salesman_id',$current_salesman->id)->whereDate('created_at','>=',$month_ago)->count();
            $total_vso = Vso::where('salesman_id',$current_salesman->id)->whereDate('vso_date','>=',$year_ago)->count();
            $new_vso = Vso::where('salesman_id',$current_salesman->id)->whereDate('vso_date','>=',$month_ago)->count();;
        }
        else
        {
            $text .= $user->name;
            $total_prospect = PresalesProspect::where('user_id',$user->id)->whereDate('created_at','>=',$year_ago)->count();
            $new_prospect = PresalesProspect::where('user_id',$user->id)->whereDate('created_at','>=',$month_ago)->count();
            $total_vso = Vso::where('created_by',$user->id)->whereDate('vso_date','>=',$year_ago)->count();
            $new_vso = Vso::where('created_by',$user->id)->whereDate('vso_date','>=',$month_ago)->count();;
        }

        $salesman = CompanyEmployee::whereIn('employee_type',['Salesman','BROKER'])->where([['status','ACTIVE']])->get();
        foreach($salesman as $u)
        {
            $u->vso_count = Vso::where('salesman_id',$u->id)->count();
            $u->prospect_count = PresalesProspect::where('salesman_id',$u->id)->count();
        }
        $salesman = $salesman->sortByDesc('vso_count')->sortByDesc('prospect_count');
        $i=0;
        $rank=$salesman->count();
        foreach($salesman as $u)
        {
            $i++;
            if($u->user_id == $user->id)
            {
                $rank = $i;
            }
        }

        $label=[];
        $data_1=[];
        $data_2=[];

        for($m=1;$m<=12;$m++)
        {
            $month = date("F", mktime(0, 0, 0, $m, 1));
            array_push($label,$month);
            $from = Carbon::parse('first day of '.$month.' '.date('Y'));
            $to = Carbon::parse('last day of '.$month.' '.date('Y'));
            if($current_salesman)
            {
                $month_prospect = PresalesProspect::where('salesman_id',$current_salesman->id)->whereBetween('created_at',[$from,$to])->count();
                $month_vso = Vso::where('salesman_id',$current_salesman->id)->whereBetween('created_at',[$from,$to])->count();;
            }
            else
            {
                $month_prospect = PresalesProspect::where('user_id',$user->id)->whereBetween('created_at',[$from,$to])->count();
                $month_vso = Vso::where('created_by',$user->id)->whereBetween('created_at',[$from,$to])->count();;
            }
            array_push($data_1,$month_prospect);
            array_push($data_2,$month_vso);
        }

        $datasets = [
            $data_1,$data_2
        ];

        $text .= ' @ '.Carbon::now();

        return ["total_prospect"=>$total_prospect,"new_prospect"=>$new_prospect,"total_vso"=>$total_vso,"new_vso"=>$new_vso,"rank"=>$rank,"label"=>$label,"datasets"=>$datasets,"text"=>$text];
    }

    public function check_identity(Request $request, $identity)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman']))
        {
            abort(403);
        }

        $list = [];
        $list_1 = SalesCustomer::where('identity',$identity)->get();
        $list_2 = PresalesProspect::where('identity',$identity)->whereNotIn('customer_id',$list_1->pluck('id'))->get();

        foreach($list_1 as $item)
        {
            $item->type = "Customer";
            array_push($list,$item);
        }

        foreach($list_2 as $item)
        {
            $item->type = "Prospect";
            array_push($list,$item);
        }

        return $list;
    }
}
