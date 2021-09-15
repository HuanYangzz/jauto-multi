<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DateTime;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use App\Models\PresalesEvent;
use App\Models\PresalesProspect;
use App\Models\PresalesProspectHistory;
use App\Models\PresalesProspectDetail;
use App\Models\PresalesProspectArchive;
use App\Models\SalesCustomer;
use App\Models\VehicleModel;
use App\Models\VehicleVariant;
use App\Models\CompanyProfile;
use App\Models\CompanyBranch;
use App\Models\CompanyBranchEmployee;
use App\Models\CompanyEmployee;
use App\Models\User;
use App\Models\FileAttachment;
use App\Models\SalesVso;
use Illuminate\Support\Arr;

class PresalesController extends Controller
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
    
    public function event_list(Request $request)
    {
        $message = $request->get('message');

        $list = PresalesEvent::all();

        return view('presales_module.event_list',['list'=>$list,'message'=>$message]);
    }

    public function event(Request $request,$code)
    {
        $message = $request->get('message');

        $item = PresalesEvent::where('code',$code)->first();
        if(!$item)
        {
            $item = new PresalesEvent();
        }

        return view('presales_module.event',['item'=>$item,'message'=>$message]);
    }

    public function update_event(Request $request)
    {
        $id = $request->get('id');
        $item = PresalesEvent::find($id);
        if(!$item)
        {
            $item = new PresalesEvent();
            $item->status = "ACTIVE";

            $year_prefix = date('y');
            $max = PresalesEvent::whereRaw('LENGTH(code) = 8')->orderBy('id','desc')->first();
            if($max)
            {
                $event_no = substr($max->code,5,4) + 1;
                $code = "EV".$year_prefix.str_pad($event_no,4,'0',STR_PAD_LEFT);
            }
            else
            {
                $code = "EV".$year_prefix."0001";
            }
            $item->code = $code;
        }

        $item->fill($request->all());
        if($item->start_at == "")
        {
            $item->start_at = null;
        }
        if($item->end_at == "")
        {
            $item->end_at = null;
        }
        $item->save();

        return redirect()->action('PresalesController@event',['code'=>$item->code]);
    }

    public function deactivate_event(Request $request)
    {
        $id = $request->get('id');
        $description = $request->get('description');
        $item = PresalesEvent::find($id);
        if(!$item)
        {
            abort(403);
        }
        $message = "\n".Carbon::today()->toFormattedDateString().": Deactivated by ".Auth::user()->name." due to [".$description."]";

        $item->status = "INACTIVE";
        $item->remarks = $item->remarks."\n".$message;
        $item->save();

        return redirect()->action('PresalesController@event',['code'=>$item->code]);
    }

    public function prospect(Request $request,$customer_code=null, $code=null)
    {
        $vehicle = VehicleModel::all();
        $variant = VehicleVariant::all();
        $event = PresalesEvent::where('status','ACTIVE')->get();
        $brand = VehicleModel::select('brand')->distinct()->get();

        $phid = null;
        $pid = null;
        $prospect = null;
        $details = null;

        if($customer_code!=null)
        {
            $prospect = PresalesProspect::where('code',$customer_code)->first();
            if($prospect)
            {
                $pid = $prospect->id;
            }
        }

        $history = PresalesProspectHistory::where('code',$code)->first();
        if($history)
        {
            $history->event = "";
            $phid = $history->id;
            $pid = $history->prospect_id;
            $created_by = CompanyEmployee::find($history->salesman_id);
            if(!$created_by)
            {
                $user = User::find($history->user_id);
                if($user)
                {
                    $history->created_by = $user->name;
                }
            }
            if($created_by)
            {
                $history->created_by = $created_by->full_name;
            }

            if($history && $history->event_id>0)
            {
                $history->event = PresalesEvent::find($history->event_id)->display_name;
            }

            $prospect = PresalesProspect::find($pid);
            if($prospect->event_id>0)
            {
                $prospect->event = PresalesEvent::find($prospect->event_id)->description;
            }
            
            $files = [];
            if($history)
            {
                $pdf = FileAttachment::where('type','prospect')->where('ref_id',$history->id)->orderBy('id','desc')->get();
                foreach($pdf as $f)
                {
                    if(strpos($f->filename,'.pdf'))
                    {
                        array_push($files, ["image"=>"/img/pdf.png","id"=>$f->id,"file"=>url($f->path),"type"=>"pdf"]);
                    }
                    else
                    {
                        array_push($files, ["image"=>url($f->path),"id"=>$f->id,"file"=>url($f->path),"type"=>"image"]);
                    }
                }
            }
            $prospect->files = $files;

            $details = PresalesProspectDetail::where([['prospect_id',$pid],['prospect_history_id',$phid]])->get();
            foreach($details as $d)
            {
                if($d->vehicle_id>0)
                {
                    $m = VehicleModel::find($d->vehicle_id);
                    $d->model = $m->brand."   ".$m->model;
                    $d->variant_list = VehicleVariant::where('vehicle_id',$m->id)->get();
                }
                if($d->variant_id>0)
                {
                    $v = VehicleVariant::find($d->variant_id);
                    $d->variant = $v->variant;
                    $d->model = $m->brand."   ".$d->model.'   '.$d->variant;
                }    
            }
        }

        $office_no="";
        $salesman = CompanyEmployee::where('user_id',Auth::Id())->first();
        if($salesman)
        {
            $upline = CompanyEmployee::find($salesman->upline_id);
            if($upline)
            {
                $prospect->upline_name = $upline->full_name;
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

        return view('presales_module.prospect_form',['variant'=>$variant,'event'=>$event,'vehicle'=>$vehicle,'brand'=>$brand,'prospect'=>$prospect,'details'=>$details,'history'=>$history,'office_no'=>$office_no,'office_name'=>$office_name]);
    }

    public function check_contact(Request $request, $contact)
    {
        $list=[];
        $list_c = SalesCustomer::where('contact',$contact)->orWhere('contact_1',$contact)->orWhere('contact_2',$contact)->orWhere('contact_home',$contact)->orWhere('contact_office',$contact)->get();
        $list_p = PresalesProspect::whereNotIn('customer_id',$list_c->pluck('id'))->where('contact',$contact)->orWhere('contact_1',$contact)->orWhere('contact_2',$contact)->orWhere('contact_home',$contact)->orWhere('contact_office',$contact)->get();

        foreach($list_c as $item)
        {
            $item->search_contact = $contact;
            $item->type = "Customer";
            array_push($list,$item);
        }
        foreach($list_p as $item)
        {
            $item->search_contact = $contact;
            $item->type = "Prospect";
            array_push($list,$item);
        }

        return $list;
    }

    public function check_name(Request $request, $name="")
    {
        if($name=="")
        {
            $name = $request->get('name');
        }

        $list=[];
        $list_c = SalesCustomer::where('name','like','%'.$name.'%')->orWhere('full_name','like','%'.$name.'%')->get();
        $list_p = PresalesProspect::whereNotIn('customer_id',$list_c->pluck('id'))->where('name','like','%'.$name.'%')->orWhere('full_name','like','%'.$name.'%')->get();

        foreach($list_c as $item)
        {
            $item->search_name = $name;
            $item->type = "Customer";
            $item->contact = $item->contact!=""?$item->contact:($item->contact_1!=""?$item->contact_1:$item->contact_2);
            array_push($list,$item);
        }
        foreach($list_p as $item)
        {
            $item->search_name = $name;
            $item->type = "Prospect";
            $item->contact = $item->contact!=""?$item->contact:($item->contact_1!=""?$item->contact_1:$item->contact_2);
            array_push($list,$item);
        }

        return $list;
    }

    public function new_prospect(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman','demo']))
        {
            abort(403);
        }

        $name = $request->input('name');
        $contact = $request->input('contact');
        $identity = $request->input('identity')?$request->input('identity'):"";
        $company_reg_no = $request->input('company_reg_no')?$request->input('company_reg_no'):"";
        $company_name = $request->input('company_name')?$request->input('company_name'):"";
        
        $prospect = new PresalesProspect();
        $prospect->name = strtoupper($name);
        $prospect->contact = $contact;
        $prospect->identity = $identity;
        $prospect->company_reg_no = $company_reg_no;
        $prospect->company = $company_name;
        $prospect->user_id = Auth::id();
        $prospect->created_by = Auth::id();
        $salesman = CompanyEmployee::where('user_id',Auth::id())->first();
        if($salesman)
        {
            $prospect->salesman_id = $salesman->id;
        }
        else
        {
            $prospect->salesman_id = 0;
        }
        $prospect->status = "ACTIVE";
        $prospect->save();
        
        $year_prefix = date('y');
        $max = PresalesProspect::whereRaw('LENGTH(code) = 8')->orderBy('id','desc')->first();
        if($max)
        {
            $prospect_no = substr($max->code,5,4) + 1;
            $code = "PS".$year_prefix.str_pad($prospect_no,4,'0',STR_PAD_LEFT);
        }
        else
        {
            $code = "PS".$year_prefix."0001";
        }
        $prospect->code = $code;
        $prospect->save();

        return response()->json(["id"=>$prospect->id]);
    }

    public function update_prospect_detail(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman','demo']))
        {
            abort(403);
        }

        $pid = $request->input('prospect_id');
        $vehicle_id = $request->input('vehicle_id');
        $variant_id = $request->input('variant_id');
        $pdid = $request->input('detail_id');
        $phid = $request->input('history_id');
        $color = $request->input('color');

        $history = PresalesProspectHistory::find($phid);

        if(!$history)
        {
            $history = new PresalesProspectHistory();
            $year_prefix = date('y');
            $max = PresalesProspectHistory::whereRaw('LENGTH(code) = 9')->orderBy('id','desc')->first();
            if($max)
            {
                $history_no = substr($max->code,5,4) + 1;
                $code = "PSH".$year_prefix.str_pad($history_no,4,'0',STR_PAD_LEFT);
            }
            else
            {
                $code = "PSH".$year_prefix."0001";
            }
            $history->code = $code;
            $history->prospect_id = $pid;
            $history->status = 'New';
            $history->user_id = Auth::id();
            $salesman = CompanyEmployee::where('user_id',Auth::id())->first();
            if($salesman)
            {
                $history->salesman_id = $salesman->id;
            }
            else
            {
                $history->salesman_id = 0;
            }
            $history->save();
        }

        $pd = PresalesProspectDetail::find($pdid);
        if(!$pd)
        {
            $pd = new PresalesProspectDetail();
            $pd->prospect_id = $pid;
            $pd->status = "New";
            $pd->prospect_history_id = $history->id;
            $pd->salesman_id = Auth::id();
            $salesman = CompanyEmployee::where('user_id',Auth::id())->first();
            if($salesman)
            {
                $pd->salesman_id = $salesman->id;
            }
            else
            {
                $pd->salesman_id = 0;
            }
        }
        else
        {
            $pd = PresalesProspectDetail::find($pdid);
            $pd->status = "Edited";
        }
        $pd->vehicle_id = $vehicle_id;
        $pd->variant_id = $variant_id?$variant_id:0;
        $v = VehicleVariant::find($variant_id);
        if($v)
        {
            $pd->vehicle_id = $v->vehicle_id;
        }
        $pd->color = $color;
        $pd->save();

        return ["message"=>"Prospect updated!","detail_id"=>$pd->id,"history"=>$history->id];
    }

    public function update_prospect_history(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman','demo']))
        {
            abort(403);
        }

        $pid = $request->input('prospect');
        $phid = $request->input('history');
        $event = $request->input('event');
        $remark = $request->input('remark');

        $prospect = PresalesProspect::find($pid);
        $history = PresalesProspectHistory::find($phid);

        if(!$history)
        {
            $history = new PresalesProspectHistory();
            $year_prefix = date('y');
            $max = PresalesProspectHistory::whereRaw('LENGTH(code) = 9')->orderBy('id','desc')->first();
            if($max)
            {
                $history_no = substr($max->code,5,4) + 1;
                $code = "PSH".$year_prefix.str_pad($history_no,4,'0',STR_PAD_LEFT);
            }
            else
            {
                $code = "PSH".$year_prefix."0001";
            }
            $history->code = $code;
            $history->prospect_id = $prospect->id;
            $history->status = 'New';
            $history->remark="";
            $history->user_id = Auth::id();
            $salesman = CompanyEmployee::where('user_id',Auth::id())->first();
            if($salesman)
            {
                $history->salesman_id = $salesman->id;
            }
            else
            {
                $history->salesman_id = 0;
            }
            $history->remark = $remark;
            $prospect->event_id = $event;
            $prospect->save();   
        }
        if($event > 0)
        {
            $history->event_id = $event;
        }
        $history->remark = $remark;
        $history->save();

        $salesman = CompanyEmployee::find($history->salesman_id);
        if($salesman)
        {
            $history->created_by = $salesman->full_name;
        }
        else
        {
            $user = User::find($history->user_id);
            $history->created_by = $user->name;
        }

        return ["message"=>"Prospect updated!","history"=>$history->id,"item"=>$history];
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

    public function remove_prospect_detail(Request $request, $hid, $id)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman']))
        {
            abort(403);
        }
        
        $detail = PresalesProspectDetail::find($id);
        if(!$detail)
        {
            abort(403);
        }
        $pid=$detail->prospect_id;
        $detail->delete();

        $history = PresalesProspectHistory::find($hid);
        if(!$history)
        {
            abort(403);
        }

        return redirect()->action('PresalesController@prospect',["code"=>$history->code]);
    }

    public function prospect_book(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman']))
        {
            abort(403);
        }
        if($request->user()->hasRole(['Manager','Admin','System Admin']))
        {
            $list = PresalesProspect::all();    
        }
        else
        {
            $salesman = CompanyEmployee::where('user_id',Auth::id())->first();
            if($salesman)
            {
                $ids = PresalesProspectHistory::where('user_id',Auth::id())->orWhere('salesman_id',$salesman->id)->pluck('prospect_id');
                $list = PresalesProspect::where('user_id',Auth::id())->orWhere('salesman_id',$salesman->id)->orWhereIn('id',$ids)->get();
            }
            else
            {
                $ids = PresalesProspectHistory::where('user_id',Auth::id())->pluck('prospect_id');
                $list = PresalesProspect::where('user_id',Auth::id())->orWhereIn('id',$ids)->get();
            }
        }
        
        foreach($list as $p)
        {
            $address = '' ;
            $address .= $p->address_1!=''?$p->address_1:'';
            $address .= $p->address_2!=''?', '.$p->address_2:'';
            $address .= $p->postcode!=''?', '.$p->postcode:'';
            $address .= $p->city!=''?', '.$p->city:'';
            $address .= $p->state!=''?', '.$p->state:'';
            $address .= $p->country!=''?', '.$p->country:'';
            $p->address =  $address;
            $tmp = $p->contact!=''?$p->contact:$p->contact_1;
            $p->contact_str = HelperFunction::convertContact($tmp);

            $who = "";
            $created_by = User::find($p->created_by);
            if($created_by)
            {
                $who = $created_by->name;
                $salesman = CompanyEmployee::where('user_id',$created_by->id)->first();
                if($salesman)
                {
                    $who = $salesman->name;
                }
            }
            $p->created_by_str = $who;

            $p->vehicle_no = PresalesProspectDetail::where('prospect_id',$p->id)->count();
        }
        $id = $request->get('id');
        $message = $request->get('message');

        $all = PresalesProspect::whereIn('id',$list->pluck('id'))->whereNotIn('status',['INACTIVE'])->get();
        $dup_no = "CHECK";

        $ids = SalesVso::all()->pluck('prospect_id')->toArray();
        $active = DB::table('presales_prospect_history')->select(DB::raw('count(1) as user_count,prospect_id'))->whereNotIn('prospect_id',$ids)->groupBy('prospect_id')->get();
        $active_no=0;
        foreach($active as $ac)
        {
            if($ac->user_count>1)
            {
                $active_no++;
            }
        }

        return view('presales_module.prospect_book',['list'=>$list,'id'=>$id,'message'=>$message,'no'=>$dup_no,'active'=>$active_no]);
    }

    public function prospect_active_check(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman']))
        {
            abort(403);
        }

        $list = [];
        $ids = Vso::all()->pluck('prospect_id')->toArray();
        $active = DB::table('presales_prospect_history')->select(DB::raw('count(1) as user_count,prospect_id'))->whereNotIn('prospect_id',$ids)->groupBy('prospect_id')->get();
        $active_no=0;
        foreach($active as $ac)
        {
            if($ac->user_count>1)
            {
                $prospect = PresalesProspect::find($ac->prospect_id);
                $history = PresalesProspectHistory::where("prospect_id",$ac->prospect_id)->orderByDesc('created_at')->get();
                if($prospect)
                {
                    foreach($history as $h)
                    {
                        $ds = PresalesProspectDetail::where('prospect_history_id',$h->id)->get();
                        foreach($ds as $d)
                        {
                            $v = VehicleVariant::find($d->variant_id);
                            if($v)
                            {
                                $vs = VehicleModel::find($v->vehicle_id);
                                $d->vehicle_model = ($vs?($vs->brand." - ".$vs->model." - "):"").$v->variant." (".$d->color.")";
                            }
                        }
                        $h->details = $ds;
                        $salesman = CompanyEmployee::find($h->salesman_id);
                        $salesman_name = " - ";
                        $salesman_contact = " - ";
                        if($salesman)
                        {
                            $salesman_name = $salesman->full_name;
                            $salesman_contact = HelperFunction::convertContact($salesman->contact);
                        }
                        $h->salesman_name = $salesman_name;
                        $h->salesman_contact = $salesman_contact;
                        $event = PresalesEvent::find($h->event_id);
                        if($event)
                        {
                            $h->event_name = $event->display_name;
                        }
                        else
                        {
                            $h->event_name = " - ";
                        }
                    }

                    $prospect->name_str = $prospect->full_name!=""?$prospect->full_name:$prospect->name;
                    $prospect->contact_str = HelperFunction::convertContact($prospect->contact);
                    $prospect->history = $history;
                    array_push($list,$prospect);
                }
            }
        }

        return $list;
    }

    public static function duplicate_check_field($field,$value,$ids)
    {
        $clone = PresalesProspect::whereNotIn('status',['INACTIVE'])->whereNotIn('id',$ids);
        
        if($value!=null && $value!="")
        {
            $found_ids = $clone->WhereRaw($field.' <>"" AND '.$field.' IS NOT NULL AND '.$field.' like "%'.$value.'%"')->pluck('id')->toArray();
            $ids = array_merge($ids,$found_ids);
        }

        $check = new \StdClass();
        $check->found_ids = $found_ids;
        $check->ids = $ids;
        return $check;
    }

    public static function recursive_duplicate_check($all,$ids,$level=0)
    {
        if($level>0) //if($level>1) //deep search
        {
            return null;
        }
        $dup_list = [];

        foreach($all as $p)
        {
            array_push($ids,$p->id);
            $checklist = [];

            foreach(["name","full_name","contact","contact_1","contact_2","contact_home","contact_office"] as $name)
            {
                if(!$p->{$name} || $p->{$name}=="")
                {
                    continue;
                }

                $wildcard = explode(" ",$p->{$name});
                $concat="";
                foreach($wildcard as $keyword)
                {
                    if($concat!="")
                    {
                        $keyword = $concat." ".$keyword;
                        $concat="";
                    }
                    if(strlen($keyword)<=7)
                    {
                        $concat = $keyword;
                        continue;
                    }

                    foreach(["name","full_name","contact","contact_1","contact_2","contact_home","contact_office"] as $field)
                    {
                        $ids = array_unique($ids);
                        $tmp = self::duplicate_check_field($field,$keyword,$ids);
                        if(sizeof($tmp->found_ids)>0)
                        {
                            $check = new \StdClass();
                            
                            $p->contact_str = HelperFunction::convertContacts([$p->contact,$p->contact_1,$p->contact_2,$p->contact_home,$p->contact_office],'<br/>');
                            $address = '' ;
                            $address .= $p->address_1!=''?$p->address_1:'';
                            $address .= $p->address_2!=''?', '.$p->address_2:'';
                            $address .= $p->postcode!=''?', '.$p->postcode:'';
                            $address .= $p->city!=''?', '.$p->city:'';
                            $address .= $p->state!=''?', '.$p->state:'';
                            $address .= $p->country!=''?', '.$p->country:'';
                            $p->address =  $address;
                            
                            $check->origin = $p;
                            $check->found_ids = $tmp->found_ids;
                            $check->search_text = $keyword;
                            $check->search_type = $field;
                            $check->level = $level;
                            
                            $pplist = PresalesProspect::whereIn('id',$tmp->found_ids)->get();
                            $rlist = self::recursive_duplicate_check($pplist,$ids,$level+1);
                            if($rlist)
                            {
                                foreach($rlist as $r)
                                {
                                    foreach($r->found_list as $rcheck)
                                    {
                                        array_push($checklist,$rcheck);                
                                    }
                                }
                            }

                            $ids = array_merge($ids,$tmp->ids);
                            
                            array_push($checklist,$check);
                        }
                    }
                }
            }
            if(sizeof($checklist)>0)
            {
                $prospect_found = new \StdClass();
                $prospect_found->found_list = $checklist;
                $prospect_found->origin = $p;
                array_push($dup_list,$prospect_found);
            }
        }

        return $dup_list;
    }

    public static function keywordCheck($list_1,$list_2,$prospect,$search_list)
    {
        $p = $prospect;
        $keywords = [];
        foreach($list_1 as $name)
        {
            if(strpos($name,"contact")!==false)
            {
                if($p->{$name} && $p->{$name}!="")
                {
                    $wildcard = [HelperFunction::convertContact($p->{$name})];
                }
            }
            else
            {
                $p->{$name.'_str'} = "<span>".$p->{$name}."</span>";
                $wildcard = explode(" ",$p->{$name});
            }
            
            $concat="";
            foreach($wildcard as $keyword)
            {
                if($concat!="")
                {
                    $keyword = $concat." ".$keyword;
                    $concat="";
                }
                if(strlen($keyword)<=7)
                {
                    $concat = $keyword;
                    continue;
                }
                array_push($keywords,$keyword);
            }
        }
        $keywords = array_unique($keywords);

        $ids = [];
        $new_list = [];
        foreach($search_list as $p)
        {
            $address = '' ;
            $address .= $p->address_1!=''?$p->address_1:'';
            $address .= $p->address_2!=''?', '.$p->address_2:'';
            $address .= $p->postcode!=''?', '.$p->postcode:'';
            $address .= $p->city!=''?', '.$p->city:'';
            $address .= $p->state!=''?', '.$p->state:'';
            $address .= $p->country!=''?', '.$p->country:'';
            $p->address =  $address;

            $salesman = CompanyEmployee::find($p->salesman_id);
            if($salesman)
            {
                $p->salesman = $salesman->name;
            }

            if(in_array($p->id,$ids))
            {
                continue;
            }

            array_push($ids,$p->id);
            $p->keywords = $keywords;
            foreach($list_2 as $name)
            {
                $p->{$name.'_str'} = "<span>".$p->{$name}."</span>";

                foreach($keywords as $k)
                {
                    $start = strpos($p->{$name.'_str'},$k);
                 
                    if($start>0)
                    {
                        $end = $start + strlen($k);
                        
                        $tmp = substr($p->{$name.'_str'},0,$start)."</span><span class='highlight-bg'>".$k."</span><span>".substr($p->{$name.'_str'},$end);
                        $p->{$name.'_str'} = $tmp;
                    }
                }
            }
            $p->contact_str = $p->contact_str_str;
            array_push($new_list,$p);
        }

        return $new_list;
    }

    public function prospect_duplicate_check(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman']))
        {
            abort(403);
        }
        $all = PresalesProspect::whereNotIn('status',['INACTIVE'])->get();

        $list = self::recursive_duplicate_check($all,[]);
        $final_list = [];
        foreach($list as $plist)
        {
            $prospect = new \StdClass();
            $prospect->origin = $plist->origin;
            $prospect->found_list = [];
            $keywords = Arr::pluck($plist->found_list,'search_text');
            
            foreach($plist->found_list as $check)
            {
                $pp = PresalesProspect::whereIn('id',$check->found_ids)->orWhere('id',$prospect->origin->id)->get();
                foreach($pp as $p)
                {
                    $p->contact_str = HelperFunction::convertContacts([$p->contact,$p->contact_1,$p->contact_2,$p->contact_home,$p->contact_office],'<br/>');

                    $address = '' ;
                    $address .= $p->address_1!=''?$p->address_1:'';
                    $address .= $p->address_2!=''?', '.$p->address_2:'';
                    $address .= $p->postcode!=''?', '.$p->postcode:'';
                    $address .= $p->city!=''?', '.$p->city:'';
                    $address .= $p->state!=''?', '.$p->state:'';
                    $address .= $p->country!=''?', '.$p->country:'';
                    $p->address =  $address;

                    array_push($prospect->found_list,$p);
                }
                $prospect->found_list = self::keywordCheck(["name","full_name","contact","contact_1","contact_2","contact_home","contact_office"],["name","full_name","contact_str"],$prospect->origin,$prospect->found_list);
            }   
            
            array_push($final_list,$prospect);
        }

        return $final_list;
    }

    public function prospect_merge(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            return "UNAUTHORIZED";
        }
        $message = $request->get('message');
        $ids_str = $request->get('ids');
        $ids = explode(',',$ids_str);
        $first = $request->get('first');

        $list = PresalesProspect::whereIn('id',$ids)->get();

        $customer = $list->find($first);
        if(!$customer)
        {
            $customer = $list->first();
        }
        
        foreach($list as $ref)
        {
            $address = '' ;
            $address .= $ref->address_1!=''?$ref->address_1:'';
            $address .= $ref->address_2!=''?', '.$ref->address_2:'';
            $address .= $ref->postcode!=''?', '.$ref->postcode:'';
            $address .= $ref->city!=''?', '.$ref->city:'';
            $address .= $ref->state!=''?', '.$ref->state:'';
            $address .= $ref->country!=''?', '.$ref->country:'';
            $ref->address =  $address;

            $salesman = CompanyEmployee::find($ref->salesman_id);
            if($salesman)
            {
                $ref->salesman_str = $salesman->full_name;
            }
        }

        $list = $list->where('id','<>',$customer->id);
        $ref = $list->where('customer_id','<>',0)->first();
        if($ref)
        {
            $customer->customer_id = $ref->customer_id;
        }

        $salesmen = CompanyEmployee::whereIn('employee_type',['Salesman','BROKER'])->where([['status','ACTIVE']])->get();

        return view('presales_module.prospect_merge',['list'=>$list,'item'=>$customer,'message'=>$message,'salesmen'=>$salesmen,'ids'=>$ids_str]);
    }

    public function prospect_merge_data(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin','Salesman']))
        {
            return "UNAUTHORIZED";
        }

        $ids_str = $request->get('ids');
        $ex_id = $request->get('ex_id');

        $c = new PresalesProspect();
        $c->fill($request->all());
        $year_prefix = date('y');
        $max = PresalesProspect::whereRaw('LENGTH(code) = 8')->orderBy('id','desc')->first();
        if($max)
        {
            $prospect_no = substr($max->code,5,4) + 1;
            $code = "PS".$year_prefix.str_pad($prospect_no,4,'0',STR_PAD_LEFT);
        }
        else
        {
            $code = "PS".$year_prefix."0001";
        }
        $c->code = $code;
        $c->save();

        $message="Data Saved";

        $ids = explode(',',$ids_str);
        array_push($ids,$ex_id);
        foreach($ids as $ex)
        {
            $ex_prospect = PresalesProspect::find($ex);
            if($ex_prospect)
            {
                $archive_prospect = new PresalesProspectArchive();
                foreach ($ex_prospect->getAttributes() as $key=>$value) {
                    $archive_prospect->{$key} = $value;
                }
                $archive_prospect->new_prospect_code = $c->code;
                $archive_prospect->save();

                $ex_history = PresalesProspectHistory::where('prospect_id',$ex)->get();
                foreach($ex_history as $history)
                {
                    $h = new PresalesProspectHistory();
                    foreach ($history->getAttributes() as $key=>$value) {
                        if($key!="id")
                        {
                            $h->{$key} = $value;
                        }
                    }
                    $h->prospect_id = $c->id;
                    $h->save();
                }

                $ex_attachment = FileAttachment::where([['type','prospect_id'],['ref_id',$ex]])->get();
                foreach($ex_attachment as $file)
                {
                    $f = new FileAttachment();
                    foreach ($file->getAttributes() as $key=>$value) {
                        if($key!="id")
                        {
                            $f->{$key} = $value;
                        }
                    }
                    $f->ref_id = $c->id;
                    $f->save();
                }

                
                $ex_customer = SalesCustomer::where('prospect_id',$ex->id);
                if($ex_customer)
                {
                    $ex_customer->update(['prospect_id',$c->id]);
                }

                $ex_prospect->delete();
            }
        }

        return redirect()->action('PresalesController@prospect_book_detail',['code'=>$c->code,'message'=>$message]);
    }

    public function prospect_book_detail(Request $request,$code)
    {
        $message = $request->get('message');
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            return "UNAUTHORIZED";
        }
        $item = PresalesProspect::where('code',$code)->first();
        if(!$item)
        {
            abort(403);
        }

        $address = '' ;
        $address .= $item->address_1!=''?$item->address_1:'';
        $address .= $item->address_2!=''?', '.$item->address_2:'';
        $address .= $item->postcode!=''?', '.$item->postcode:'';
        $address .= $item->city!=''?', '.$item->city:'';
        $address .= $item->state!=''?', '.$item->state:'';
        $address .= $item->country!=''?', '.$item->country:'';
        $item->address =  $address;

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

        if(!$request->user()->hasRole(['System Admin','Admin','Manager']))
        {
            $current_salesman = CompanyEmployee::where('user_id',Auth::id())->first();
            if($current_salesman)
            {
                $history = PresalesProspectHistory::where([['prospect_id',$item->id],['salesman_id',$current_salesman->id]])->orWhere([['prospect_id',$c->id],['user_id',Auth::id()]])->get();
            }
            else
            {
                $history = PresalesProspectHistory::where([['prospect_id',$item->id],['user_id',Auth::id()]])->get();
            }
        }
        else
        {
            $history = PresalesProspectHistory::where('prospect_id',$item->id)->get();
        }
        $ids = $history->pluck('id')->toArray();
        //$attachment = FileAttachment::where('type','prospect')->whereIn('ref_id',$ids)->get();
        if($ids)
        {
            $attachment = FileAttachment::whereRaw('(type = "prospect" and ref_id in ('.implode($ids,",").')) or (type="prospect_id" and ref_id = '.$item->id.')')->get();
        }
        else
        {
            $attachment = FileAttachment::whereRaw('(type="prospect_id" and ref_id = '.$item->id.')')->get();
        }

        foreach($history as $h)
        {
            $h->created_at_str = $h->created_at->format('Y-m-d');
            if($h->salesman_id > 0)
            {
                $h->salesman = CompanyEmployee::find($h->salesman_id)->name;    
            }
            else
            {
                $h->salesman = User::find($h->user_id)->name;       
            }
            
            if($h->event_id > 0)
            {
                $h->event = PresalesEvent::find($h->event_id)->display_name;
            }
            else
            {
                $h->event = ' - ';
            }
            $h->details = PresalesProspectDetail::where('prospect_history_id',$h->id)->get();
            foreach($h->details as $d)
            {
                $d->vehicle = VehicleModel::find($d->vehicle_id);
                $d->variant = VehicleVariant::find($d->variant_id);
            }
        }

        return view('presales_module.prospect_book_detail',['item'=>$item,'message'=>$message,'salesmen'=>$salesmen,"history"=>$history,'attachment'=>$attachment,'message'=>$message]);
    }

    public function save_prospect(Request $request)
    {
        $message = $request->get('message');
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            return "UNAUTHORIZED";
        }
        $id = $request->get('id');
        $item = PresalesProspect::find($id);
        if(!$item)
        {
            abort(403);
        }
        $item->fill($request->all());
        $item->save();

        return redirect()->action('PresalesController@prospect_book_detail',['code'=>$item->code,'message'=>$message]);
    }
}
