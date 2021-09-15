<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DateTime;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use App\Models\VehicleModel;
use App\Models\VehicleVariant;
use App\Models\VehicleModelAccessory;
use App\Models\VehicleAccessory;
use App\Models\InventoryStockTransfer;
use App\Models\InventoryStock;
use App\Models\InventoryNonStock;
use App\Models\InventoryFeeGroup;
use App\Models\InventoryFee;
use App\Models\User;
use App\Models\CompanyBranch;
use App\Models\CompanyEmployee;
use App\Models\CompanyProfile;
use Spatie\Permission\Models\Role;
use PDF;


class InventoryController extends Controller
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

    public function get_model_individual($brand)
    {
        $model = VehicleModel::where('brand',$brand)->where('is_commercial',0)->get();

        return $model;
    }

    public function get_model_commercial($brand)
    {
        $model = VehicleModel::where('brand',$brand)->get();

        return $model;
    }

    public function get_vehicle_model(Request $request, $brand)
    {
        if($brand!="")
        {
            $model = VehicleModel::where('brand',$brand)->get();
        }
        else
        {
            $model = VehicleModel::all();
        }
        foreach($model as $v)
        {
            $v->text = $v->brand." - ".$v->model;
        }

        return $model;
    }

    public function get_variant_detail($id)
    {
        $variant = VehicleVariant::find($id);
        $vehicle = VehicleModel::find($variant->vehicle_id);
        $variant->model = $vehicle->brand.' '.$vehicle->model;

        $acc_list = [];
        $list = VehicleModelAccessory::where([['vehicle_variant_id',$id],['type','DEFAULT']])->get();
        foreach($list as $va)
        {
            $acc = VehicleAccessory::find($va->accessory_id);
            if($acc)
            {
                $acc->remark = $acc->description;
                $acc->price = $acc->cost;
                $acc->accessory_id = $acc->id;
                array_push($acc_list,$acc);
            }
        }
        $variant->accessories = $acc_list;

        return $variant;
    }

    public function get_variant($id=0)
    {
        $list = [];
        if($id>0)
        {
            $variant = VehicleVariant::where([['vehicle_id',$id],['status','ACTIVE']])->orderBy('variant')->get();
        }
        else
        {
            $variant = VehicleVariant::where([['status','ACTIVE']])->orderBy('vehicle_id')->orderBy('variant')->get();
        }

        foreach($variant as $v)
        {
            $vehicle = VehicleModel::find($v->vehicle_id);
            if(!$vehicle)
            {
                continue;
            }

            $v->brand = $vehicle->brand;
            $v->model = $vehicle->model;
            $v->vehicle_model = $v->variant;
            $v->text = $v->brand." - ".$v->model." - ".$v->variant;
            $tmp = HelperFunction::search_array($list,'vehicle_model',$v->vehicle_model);
            if(!$tmp)
            {
                array_push($list,$v);
            }
            else
            {
                $tmp->variant = $tmp->vehicle_model." (".$tmp->make.")";
                $tmp->text = $tmp->brand." - ".$tmp->model." - ".$tmp->vehicle_model." (".$tmp->make.")";
                $v->variant = $v->vehicle_model." (".$v->make.")";
                $v->text = $v->brand." - ".$v->model." - ".$v->vehicle_model." (".$v->make.")";
            }
        }

        return $variant;
    }

    public function vehicle_listing(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $message = $request->input('message');

        $list = VehicleVariant::all();
        foreach($list as $v)
        {
            $v->model = VehicleModel::find($v->vehicle_id)->model;
            $v->brand = VehicleModel::find($v->vehicle_id)->brand;
        }

        return view('inventory_module.vehicle_list',['list'=>$list,'message'=>$message]);
    }

    public function update_vehicle_status(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');

        $variant = VehicleVariant::find($request->input('id'));
        if(!$variant)
        {
            abort(403);
        }

        $variant->status = $request->get('status');
        $variant->save();

        return redirect()->action('InventoryController@vehicle_listing',['message'=>'Data Saved']);
    }

    public function edit_vehicle_min_price(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');
        $value = $request->input('value');

        $vehicle = VehicleVariant::find($id);
        $vehicle->min_price = $value;
        $vehicle->save();

        return "OK";
    }

    public function edit_vehicle_cost(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');
        $value = $request->input('value');

        $vehicle = VehicleVariant::find($id);
        $vehicle->cost = $value;
        $vehicle->save();

        return "OK";
    }

    public function edit_vehicle_price(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');
        $value = $request->input('value');

        $vehicle = VehicleVariant::find($id);
        $vehicle->price = $value;
        $vehicle->save();

        return "OK";
    }

    public function vehicle_stock(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $message = $request->get('message');

        $list = InventoryStock::orderBy('id','desc')->get();
        foreach($list as $s)
        {
            $s->vehicle = VehicleModel::find($s->vehicle_id);
            if($s->vehicle)
            {
                $s->brand = $s->vehicle->brand;
                $s->model = $s->vehicle->model;
            }
            $vv = VehicleVariant::find($s->variant_id);
            $s->vehicle_variant = $vv;
            if($vv)
            {
                $s->variant = $vv->variant;
                $s->make = $vv->make;
            }
            $user = User::find($s->created_by);
            if($user)
            {
                $salesman = CompanyEmployee::where('user_id',$user->id)->first();
                $s->created_by_who = $salesman?$salesman->name:$user->name;
            }
            else
            {
                $s->created_by_who = "";
            }
            $branch = CompanyBranch::find($s->branch_id);
            if($branch)
            {
                $s->branch_name = $branch->name;
            }
        }

        return view('inventory_module.vehicle_stock',['list'=>$list,'message'=>$message]);
    }

    public function stock_listing(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $message = $request->get('message');

        $list = VehicleAccessory::all();

        return view('inventory_module.stock_list',['list'=>$list,'message'=>$message]);
    }

    public function update_stock_status(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');

        $stock = VehicleAccessory::find($request->input('id'));
        if(!$stock)
        {
            abort(403);
        }

        $stock->status = $request->get('status');
        $stock->save();

        return redirect()->action('InventoryController@stock_listing',['message'=>'Data Saved']);
    }

    public function edit_stock_min_price(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');
        $value = $request->input('value');

        $stock = VehicleAccessory::find($id);
        $stock->min_price = $value;
        $stock->save();

        return "OK";
    }

    public function edit_stock_cost(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');
        $value = $request->input('value');

        $stock = VehicleAccessory::find($id);
        $stock->cost = $value;
        $stock->save();

        return "OK";
    }

    public function edit_stock_price(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');
        $value = $request->input('value');

        $stock = VehicleAccessory::find($id);
        $stock->recommended_selling_price = $value;
        $stock->save();

        return "OK";
    }

    public function non_stock_listing(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $message = $request->get('message');

        $list = InventoryNonStock::all();

        return view('inventory_module.non_stock_list',['list'=>$list,'message'=>$message]);
    }

    public function update_nonstock_status(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');

        $nonstock = InventoryNonStock::find($request->input('id'));
        if(!$nonstock)
        {
            abort(403);
        }

        $nonstock->status = $request->get('status');
        $nonstock->save();

        return redirect()->action('InventoryController@non_stock_listing',['message'=>'Data Saved']);
    }

    public function edit_nonstock_min_price(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');
        $value = $request->input('value');

        $nonstock = InventoryNonStock::find($id);
        $nonstock->min_price = $value;
        $nonstock->save();

        return "OK";
    }

    public function edit_nonstock_cost(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');
        $value = $request->input('value');

        $nonstock = InventoryNonStock::find($id);
        $nonstock->cost = $value;
        $nonstock->save();

        return "OK";
    }

    public function edit_nonstock_price(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');
        $value = $request->input('value');

        $nonstock = InventoryNonStock::find($id);
        $nonstock->recommended_selling_price = $value;
        $nonstock->save();

        return "OK";
    }

    public function fee_listing(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $message = $request->get('message');

        $list = InventoryFeeGroup::where('status','ACTIVE')->get();

        return view('inventory_module.fee_list',['list'=>$list,'message'=>$message]);
    }

    public function fee(Request $request,$type)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $message = $request->get('message');

        $group = InventoryFeeGroup::where('item_code',$type)->first();
        if(!$group)
        {
            return view('inventory_module.fee',['list'=>[],'message'=>$message,'type'=>$type,'item'=>$group]);
        }

        $list = VehicleVariant::where('status','Active')->orderBy('vehicle_id')->orderBy('variant')->get();
        foreach($list as $item)
        {
            $vehicle = VehicleModel::find($item->vehicle_id);
            $item->brand = $vehicle->brand;
            $item->model = $vehicle->model;

            $fee = InventoryFee::where([['type',$type],['vehicle_id',$item->vehicle_id],['variant_id',$item->id]])->first();
            if(!$fee)
            {
                $fee = new InventoryFee();
                $fee->vehicle_id = $item->vehicle_id;
                $fee->variant_id = $item->id;
                $fee->item_code = $type;
                $fee->type = $type;
                $fee->description = $type;
                $fee->status = "ACTIVE";
                $fee->save();
            }
            $item->id = $fee->id;
            $item->cost = $fee->cost;
            $item->price_1 = $fee->price_1;
            $item->price_2 = $fee->price_2;
            $item->price_3 = $fee->price_3;
        }

        return view('inventory_module.fee',['list'=>$list,'message'=>$message,'type'=>$type,'group'=>$group]);
    }
    
    public function update_fee(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $type = $request->input('type');

        $fee = InventoryFee::find($request->input('id'));
        if($fee)
        {
            if($type==1)
            {
                $fee->price_1 = $request->input('value');
            }
            else if($type==2)
            {
                $fee->price_2 = $request->input('value');
            }
            else if($type==3)
            {
                $fee->price_3 = $request->input('value');
            }
            else if($type==0)
            {
                $fee->cost = $request->input('value');
            }

            $fee->save();
        }

        return "OK";
    }

    public function update_fee_group(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $id = $request->input('id');

        $fee = InventoryFeeGroup::find($request->input('id'));
        if(!$fee)
        {
            abort(403);
        }

        $fee->status = $request->get('status');
        $fee->save();

        return redirect()->action('InventoryController@fee',['type'=>$fee->item_code,'message'=>'Data Saved']);
    }

    public function stock_distribution(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $message = $request->get('message');
        $message_type = $request->get('message_type');

        $branches = CompanyBranch::all();

        $ids = InventoryStockTransfer::pluck('stock_id');
        $list = InventoryStock::where('vso_id',0)->whereNotIn('id',$ids)->get();

        foreach($branches as $branch)
        {
            $blist = InventoryStock::where('branch_id',$branch->id)->get();
            foreach($blist as $s)
            {
                $s->vehicle = VehicleModel::find($s->vehicle_id);
                $s->brand = $s->vehicle->brand;
                $s->model = $s->vehicle->model;
                $s->vehicle_variant = VehicleVariant::find($s->variant_id);
                $s->variant = $s->vehicle_variant->variant;
                $user = User::find($s->created_by);
                if($user)
                {
                    $salesman = CompanyEmployee::where('user_id',$user->id)->first();
                    $s->created_by_who = $salesman?$salesman->name:$user->name;
                }
                else
                {
                    $s->created_by_who = "";
                }
                
                $s->branch_name = $branch->name;
            }
            $branch->list = $blist;
        }

        $rlist = InventoryStock::whereNotIn('branch_id',$branches->pluck('id'))->get();
        foreach($rlist as $s)
        {
            $s->vehicle = VehicleModel::find($s->vehicle_id);
            $s->brand = $s->vehicle->brand;
            $s->model = $s->vehicle->model;
            $s->vehicle_variant = VehicleVariant::find($s->variant_id);
            $s->variant = $s->vehicle_variant->variant;
            $user = User::find($s->created_by);
            if($user)
            {
                $salesman = CompanyEmployee::where('user_id',$user->id)->first();
                $s->created_by_who = $salesman?$salesman->name:$user->name;
            }
            else
            {
                $s->created_by_who = "";
            }
            $branch = CompanyBranch::find($s->branch_id);
            if($branch)
            {
                $s->branch_name = $branch->name;
            }
        }

        $st_list_codes = InventoryStockTransfer::whereIn('status',['NEW','COMPLETED'])->orderBy('code','desc')->groupBy('code')->pluck('code');
        $st_list = [];
        foreach($st_list_codes as $st_code)
        {
            $st = InventoryStockTransfer::where('code',$st_code)->first();
            $st->stock_no = InventoryStockTransfer::where('code',$st->code)->count();
            $from = CompanyBranch::find($st->branch_from);
            if($from)
            {
                $st->from_name = $from->name;
                $st->from_code = $from->code;
            }
            else
            {
                $st->from_name = "UNASSIGNED";
                $st->from_code = "";
            }
            $to = CompanyBranch::find($st->branch_to);
            if($to)
            {
                $st->to_name = $to->name;
                $st->to_code = $to->code;
            }
            else
            {
                $st->to_name = "UNASSIGNED";
                $st->to_code = "";
            }
            $st->transfer_date = Carbon::createFromFormat('Y-m-d',$st->transfer_date);
            $st->transfer_duration = $st->transfer_date->diffForHumans();
            array_push($st_list,$st);
        }

        return view('inventory_module.stock_transfer',['list'=>$list,'message'=>$message,'message_type'=>$message_type,'branches'=>$branches,'rlist'=>$rlist,'st_list'=>$st_list]);
    }

    public function confirm_transfer_out(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $code = $request->get('code');
        $stlist = InventoryStockTransfer::where('code',$code)->get();
        foreach($stlist as $st)
        {
            $st->status = "COMPLETED";    
            $st->save();
            $stock = InventoryStock::find($st->stock_id);
            if($stock)
            {
                $stock->branch_id = $st->branch_to;
                $stock->save();
            }
        }
        return redirect()->action('InventoryController@stock_distribution',['message'=>'Date Saved','message_type'=>'sucess']);
    }

    public function transfer_stock(Request $request)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        $message = $request->get('message');
        $type = $request->get('type');
        $branch_from = $request->get('branch_from');
        $branch_to = $request->get('branch_to');
        
        $ids = $request->get('model_assign');
        if(!$ids)
        {
            $ids = [];
        }

        $to = CompanyBranch::find($branch_to);

        $stock_list = InventoryStock::whereIn('id',$ids)->get();

        $year_prefix = date('y');
        $max_code = InventoryStockTransfer::where('code','LIKE','ST%')->orderByDesc('code')->first();
        if($max_code)
        {
            $code_no = intval(substr($max_code->code,-4))+1;
        }
        else
        {
            $code_no = 1;
        }
        $code = "ST".$year_prefix.str_pad($code_no,4,'0',STR_PAD_LEFT);

        foreach($stock_list as $stock)
        {
            $tmp = InventoryStockTransfer::where([['code',$code],['stock_id',$stock->id]])->first();
            if(!$tmp)
            {
                $tmp = new InventoryStockTransfer();
                $tmp->code = $code;
            }
            if($to)
            {
                $tmp->branch_from = $stock->branch_id;
                $tmp->branch_to = $to->id;
            }
            $tmp->stock_id = $stock->id;
            $tmp->created_by = Auth::Id();
            $tmp->transfer_date = Carbon::now();
            $tmp->status = "NEW";
            $tmp->save();
        }

        return redirect()->action('InventoryController@stock_distribution',['message'=>'Date Saved','message_type'=>'sucess']);
    }

    public function get_stock_transfer(Request $request, $code)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }

        $stock_list = InventoryStockTransfer::where('code',$code)->get();
        foreach($stock_list as $st)
        {
            $stock = InventoryStock::find($st->stock_id);
            if($stock)
            {
                $vehicle = VehicleModel::find($stock->vehicle_id);
                $variant = VehicleVariant::find($stock->variant_id);
                $st->model = $vehicle->brand." - ".$vehicle->model." - ".$variant->variant;
                $st->innerText = $stock->color." - ".$stock->chassis_no;
            }
            $from = CompanyBranch::find($st->branch_from);
            if($from)
            {
                $st->from_name = $from->branch_code." - ".$from->name;
            }
            else
            {
                $st->from_name = "UNASSIGNED";
            }
            $to = CompanyBranch::find($st->branch_to);
            if($to)
            {
                $st->to_name = $to->branch_code." - ".$to->name;
            }
            else
            {
                $st->to_name = "UNASSIGNED";
                $st->to_code = "";
            }
        }
        
        return ['list'=>$stock_list];
    }

    public function download_stock_transfer(Request $request, $type, $code)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        
        $stock_list = InventoryStockTransfer::where('code',$code)->get();
        foreach($stock_list as $st)
        {
            $stock = InventoryStock::find($st->stock_id);
            if($stock)
            {
                $vehicle = VehicleModel::find($stock->vehicle_id);
                $variant = VehicleVariant::find($stock->variant_id);
                $st->model = $vehicle->brand." - ".$vehicle->model." - ".$variant->variant;
                $st->innerText = $stock->color." - ".$stock->chassis_no;
                $st->color = $stock->color;
                $st->chassis_no = $stock->chassis_no;
                $st->engine_no = $stock->engine_no;
                $st->reg_no = $stock->carplate;
            }
            $from = CompanyBranch::find($st->branch_from);
            if($from)
            {
                $st->from_name = $from->branch_code." - ".$from->name;
            }
            else
            {
                $st->from_name = "UNASSIGNED";
            }
            $to = CompanyBranch::find($st->branch_to);
            if($to)
            {
                $st->to_name = $to->branch_code." - ".$to->name;
            }
            else
            {
                $st->to_name = "UNASSIGNED";
                $st->to_code = "";
            }
            $from_name = $st->from_name;
            $to_name = $st->to_name;
        }

        $user = Auth::user();
        $current_salesman = CompanyEmployee::where('user_id',$user->id)->first();

        $text = "Generated by ";

        if($current_salesman)
        {
            $text .= $current_salesman->full_name;
        }
        else
        {
            $text .= $user->name;
        }
        $text .= ' @ '.Carbon::now();
        $footerHtml = view()->make('inventory_module.pdfFooter',['text'=>$text])->render();

        $company = CompanyProfile::first();
        if($company)
        {
            $company->phone_1_str = HelperFunction::convertContact($company->phone_1);
            $company->fax_no_str = HelperFunction::convertContact($company->fax_no);
            $address = '' ;
            $address .= $company->mail_address_1!=''?$company->mail_address_1:'';
            $address .= $company->mail_address_2!=''?', '.$company->mail_address_2:'';
            $address .= $company->mail_postcode!=''?', '.$company->mail_postcode:'';
            $address .= $company->mail_city!=''?', '.$company->mail_city:'';
            $address .= $company->mail_state!=''?', '.$company->mail_state:'';
            $address .= $company->mail_country!=''?', '.$company->mail_country:'';
            $company->address = $address;
        }

        $pdf = PDF::loadView('inventory_module.stock_out',['list'=>$stock_list,'company'=>$company,'code'=>$code,'from'=>$from_name,'to'=>$to_name,'type'=>$type])
        ->setOption('footer-html', $footerHtml)
        ->setOption('margin-bottom', 10)
        ->setPaper('A4');

        return $pdf->stream("InventoryStock Out - ".$code.".pdf");                
    }

    public function get_stock(Request $request,$id=0)
    {
        if(!$request->user()->hasRole(['Manager','Branch Manager','Admin','System Admin']))
        {
            abort(403);
        }
        
        $stock_list = InventoryStock::where([['vso_id',0],['branch_id',$id]])->get();

        foreach($stock_list as $stock)
        {
            $variant = VehicleVariant::find($stock->variant_id);
            $vehicle = VehicleModel::find($variant->vehicle_id);    
            if($variant && $vehicle)
            {
                $stock->brand = $vehicle->brand;
                $stock->series = $vehicle->model;
                $stock->model = $variant->variant;
                $stock->header = $vehicle->brand." - ".$vehicle->model." - ".$variant->variant;
                $stock->text = $stock->color." - ".$stock->chassis_no;
            }
        }
        
        return $stock_list;
    }
}
