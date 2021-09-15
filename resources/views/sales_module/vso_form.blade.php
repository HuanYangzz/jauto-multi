@extends('layouts.layout')

@push('stylesheets')
<style>
    .v-variant .cs-placeholder{
        display: none !important;
      }

    .vehicle-group{
        display: contents;
    }

    .active{
        color: #fff;
        background-color: #5f76e8;
        border-color: #5f76e8;
    }
</style>
@endpush

@section('main_container')

<div class="page-breadcrumb">
  <div class="row">
      <div class="col-7 align-self-center">
          <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Good Day {{Auth::user()->name}}!</h3>
          <div class="d-flex align-items-center">
              <nav aria-label="breadcrumb">
                  <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Sales</a>
                    </li>
                      <li class="breadcrumb-item"><a href="{{url()->current()}}">Vso Form</a>
                      </li>
                  </ol>
              </nav>
          </div>
      </div>
  </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <form id="form-vso" method="POST" action="#" onsubmit="return false">
        <input type="hidden" id="company-id" value="{{$vso?$vso->company_id:""}}">
        <input type="hidden" id="customer-type" value="{{$vso?"Customer":""}}">
        <input type="hidden" id="customer-id" value="{{$vso?$vso->customer_id:""}}">
        <input type="hidden" id="vso-id" value="{{$vso?$vso->id:""}}">
        <input type="hidden" id="hidden-color" value="{{$vso?$vso->color:""}}">
        <input type="hidden" id="vehicle-id" value="{{$vso?$vso->vehicle_id:""}}">

        <div class="card">
            <div class="card-header">
                <h4>VSO Form</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label>VSO Type
                            </label>
                            <input type="hidden" name="hidden_vso_type" id="vso_type" value="{{$vso?$vso->vso_type:""}}">
                        
                            <div class="input-group">
                                <button type="button" id="vso_type_1" class="btn btn-outline-primary btn-lg btn-vso-type" data-toggle-class="btn-primary" data-toggle-passive-class="btn-outline-primary" onclick="check_type('INDIVIDUAL',true)">INDIVIDUAL
                                </button>
                                <button type="button" id="vso_type_2" class="btn btn-outline-primary btn-lg btn-vso-type" data-toggle-class="btn-primary" data-toggle-passive-class="btn-outline-primary" onclick="check_type('COMPANY PRIVATE',true)">COMPANY PRIVATE
                                </button>
                                <button type="button" id="vso_type_3" class="btn btn-outline-primary btn-lg btn-vso-type" data-toggle-class="btn-primary" data-toggle-passive-class="btn-outline-primary" onclick="check_type('COMPANY COMMERCIAL',true)">COMPANY COMMERCIAL
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-5">
                        <div class="form-group" id="divCompanyNo">
                            <label class="control-label" for="">Company Registration No <span class="required"></span>
                            </label>
                            <div class="input-group">
                                <input type="text" id="company" name="input_company" class="form-control" value="{{$vso?$vso->company_reg_no:""}}" required disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="$('#company').trigger('change')" @if($vso&&$vso->company_reg_no!="") disabled @endif><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="divCompanyName">
                            <label class="control-label" for="">Company Name <span class="required"></span>
                            </label>
                            <div class="input-group">
                                <input type="text" id="companyname" name="input_company_name" class="form-control" value="{{$vso?$vso->company_name:""}}" required disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="$('#companyname').trigger('change')" @if($vso&&$vso->company_name!="") disabled @endif><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>IC No</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="identity" name="identity" value="{{$vso?$vso->identity:""}}" required disabled onchange="check_identity_db()" oninput="identity_helper(this)">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="$('#identity').trigger('change')" @if($vso&&$vso->identity!="") disabled @endif><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Contact No</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="contact" name="contact" value="{{$vso?$vso->contact:''}}" required  disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="$('#contact').trigger('change')" @if($vso&&$vso->contact!="") disabled @endif><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <div class="input-group">
                                <input type="text" id="name" class="form-control" value="{{$vso?($vso->full_name!=''?$vso->full_name:$vso->name):""}}" required disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="$('#name').trigger('change')" @if($vso&&$vso->contact!="") disabled @endif><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @hasanyrole("System Admin|Manager|Branch Manager|Admin")
                    <div class="col-xs-none col-sm-1"></div>
                    <div class="col-xs-12 col-sm-5">
                        <div class="form-group">
                            <label>VSO No</label>
                            <input type="text" class="form-control" id="vso_no" value="{{$vso?$vso->vso_no:''}}" placeholder="AUTO GENERATE" readonly>
                        </div>
                        <div class="form-group">
                            <label>Created By</label>
                            <input type="text" class="form-control" id="created_by" value="{{$vso?$vso->created_by:''}}" placeholder="AUTO GENERATE" readonly>
                        </div>
                        <div class="form-group">
                            <label>Created At</label>
                            <input type="datetime" class="form-control" id="created_at" value="{{$vso?$vso->created_at:''}}" placeholder="AUTO GENERATE" readonly>
                        </div>
                    </div>
                    @endhasrole
                </div>
            </div>
        </div>
    </form>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div id="vehicle_group_0" class="vehicle-group">
                    <div class="col-xs-12 col-sm-5">
                        <input type="hidden" id="vso_detail_0" value=""/>
                        <input type="hidden" id="vehicle_id_0" value=""/>
                        <div class="form-group">
                            <label class="control-label" for="">Vehicle Model <span class="required"></span>
                            </label>
                        
                            <div class="block">
                                <select class="cs-select cs-skin-boxes v-vehicle" name="vehicle_0" id="vehicle_0">
                                <option value="" data-class='cs-sub-head' selected>Brand</option>
                                @foreach($brand as $b)
                                    <option value='{{$b->brand}}' data-brand='{{$b->brand}}' data-type=''>{{$b->brand}}</option>
                                @endforeach
                                </select>
                            <select class="cs-select cs-skin-boxes v-variant" name="variant_0" id="variant_0" style="display:none">
                                <option value="" data-class='cs-sub-head' selected>Vehicle Variant</option>
                                @foreach($variant as $v)
                                    <option value='{{$v->id}}'>{{$v->variant}}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="">Vehicle Color <span class="required"></span>
                            </label>
                        
                            <div class="block">
                            <select class="cs-select cs-skin-boxes" name="color_0" id="color_0">
                                    <option value="" data-class='cs-sub-head' selected>Select Color</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-outline-danger btn-remove" type="button" onclick="remove_detail(0)"><i class="fa fa-ban"></i></button>
                            <button class="btn btn-outline-primary pull-right" type="button" onclick="addVehicle()"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="col-xs-none col-sm-1"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="form-group">
                        <label>Payment Type
                        </label>
                        <input type="hidden" name="cash_payment" id="cash_payment" value="{{$vso?$vso->cash_payment:""}}">
                    
                        <div class="input-group">
                            <button class="btn btn-outline-primary btn-lg btn-vso-type" data-toggle-class="btn-primary" data-toggle-passive-class="btn-outline-primary" onclick="update_vso()">BANK LOAN
                            </button>
                            <button class="btn btn-outline-primary btn-lg btn-vso-type" data-toggle-class="btn-primary" data-toggle-passive-class="btn-outline-primary" onclick="update_vso()">CASH PAYMENT
                            </button>
                        </div>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <div class="modal-footer-left">
                <button type="button" class='btn btn-list' onclick="window.location.href='{{url('/sales/vso_list')}}'"><i class="fa fa-list fa-lg"></i></button>
            </div>
            <div class="modal-footer-right">
                <button type="button" id='btndone' class='btn btn-primary' data-toggle="modal" data-target="#review" disabled><i class="fa fa-save fa-lg"></i></button>
            </div>
        </div>
    </div>
</div>

@push('modal')
<div class="modal fade" id="check_customer" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Customer Matched</h4>
        </div>
        <div class="modal-body text-center">
          <h4>Kindly Confirm</h4>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="table-responsive">
              <table class="table table-bordered text-center" id="table_customer">
                <thead>
                  <tr>
                    <td>Identity</td>
                    <td>Full Name</td>
                    <td>Contact</td>
                    <td>Address</td>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            </div>
          </div>
          
          <h4>Please contact {{$office_name}} to verify: </h4>
          <h4><u><a href="tel://{{$office_no}}">{{$office_no}}</a></u></h4>

          <input type="hidden" id="pc_customer_type">
          <input type="hidden" id="pc_customer_id">
          <input type="hidden" id="pc_customer_name">
          <input type="hidden" id="pc_customer_full_name">
          <input type="hidden" id="pc_customer_contact">
          <input type="hidden" id="pc_customer_address_1">
          <input type="hidden" id="pc_customer_address_2">
          <input type="hidden" id="pc_customer_postcode">
          <input type="hidden" id="pc_customer_city">
          <input type="hidden" id="pc_customer_state">
          <input type="hidden" id="pc_customer_country">
          <input type="hidden" id="match_type">
      </div>
        <div class="modal-footer">
            <div class="modal-footer-left">
                <button type="button" class="btn btn-default pull-left" onclick="cancel_match_customer()"><i class="fa fa-times fa-lg"></i></button>
            </div>
            <div class="modal-footer-right">
                <button type="button" class="btn btn-outline-dark" onclick="no_match_customer()">No - Create New Contact</button>
                <button type="button" class="btn btn-outline-primary" onclick="match_customer()">Yes - This is the person</button>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="check_company" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Company Matched</h4>
        </div>
        <div class="modal-body text-center">
            <h4>Kindly Confirm</h4>
          <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="table_company">
                        <thead>
                          <tr>
                            <td>Reg No</td>
                            <td>Company Name</td>
                            <td>Contact</td>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
            </div>
            </div>
          </div>
          <h4>Please contact {{$office_name}} to verify: </h4>
          <h4><u><a href="tel://{{$office_no}}">{{$office_no}}</a></u></h4>
          <input type="hidden" id="p_c_id">
      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" onclick="match_company()">Yes - This is the company</button>
          <button type="button" class="btn btn-default" onclick="no_match_company()">No - Create new company</button>
          <button type="button" class="btn btn-default pull-left" onclick="cancel_match_company()"><i class="fa fa-times fa-lg"></i></button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="review" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Review</h4>
        </div>
        <div class="modal-body text-center">
          <h5>Well Done! {{Auth::user()->name}}</h5>
          <h5>You are now Rank <span id="review_rank"></span></h5>
          <div class="row">
              <div class="col-xs-12 col-sm-12">
                  <div style="text-align: center; overflow: hidden; margin: 10px 5px 0;">
                    <canvas id="canvas_statistic" height="200"></canvas>
                  </div>
              </div>
          </div>
          <div class="row">
          <div class="col-sm-12 col-xs-12">
              <div class="table-responsive">
            <table class="table table-bordered text-center">
              <tr>
                <th></th>
                <th>Prospect</th>
                <th>Sales</th>
              </tr>
              <tr>
                <th>Current Month ({{date('F')}})</th>
                <td><span id="review_new_prospect"></span></td>
                <td><span id="review_new_vso"></span></td>
              </tr>
              <tr>
                <th>Current Year ({{date('Y')}})</th>
                <td><span id="review_total_prospect"></span></td>
                <td><span id="review_total_vso"></span></td>
              </tr>
            </table>
          </div>
          </div>
          <br />
          <div class="row">
            <div class="col-md-12 col-xs-12">
          
        </div>
        </div>
        </div>
      </div>
        <div class="modal-footer">
            <div class="modal-footer-left">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times fa-lg"></i></button>
            </div>
            <div class="modal-footer-right">
                <button type="button" class="btn btn-primary" onclick="next()"><i class="fa fa-plus fa-lg"></i></button>
            </div>
        </div>
      </div>
    </div>
  </div>
@endpush

@endsection

@push('scripts')
<script>
var first = false;

function addVehicle()
    {
      var $vcdiv = $('div[id^="vehicle_group_"]:last');
      var no = parseInt($vcdiv.prop("id").match(/\d+/g),10)+1;

      if($("#vso_detail_"+(no-1)).val()=="")
      {
        return;
      }

      var $vcclone = $vcdiv.clone().prop('id','vehicle_group_'+no);
      var $removelink = $vcclone.find('.btn-remove');

      $removelink.attr('onclick','remove_detail('+no+')');

      $vcdiv.after($vcclone);

      var $vhidden = $vcclone.find('input[id^="vso_detail_"]:first');
      $vhidden.prop('id','vso_detail_'+no);
      $vhidden.val("");

      var $vhiddenv = $vcclone.find('input[id^="vehicle_id_"]:first');
      $vhiddenv.prop('id','vehicle_id_'+no);
      $vhiddenv.val("");

      var $vselection = $vcclone.find('select[id^="vehicle_"]:first');
      $vselection.prop('id','vehicle_'+no);
      $vselection.val("");

      var $vvselection = $vcclone.find('select[id^="variant_"]:first');
      $vvselection.prop('id','variant_'+no);
      $vvselection.prop('name','variant_'+no);
      $vvselection.val("");
      var $vvoptions = $vvselection.find('option[value!=""]');
      $vvoptions.remove();

      var $vcselection = $vcclone.find('select[id^="color_"]:first');
      $vcselection.prop('id','color_'+no);
      $vcselection.prop('name','color_'+no);
      $vcselection.val("");
      var $vcoptions = $vcselection.find('option[value!=""]');
      $vcoptions.remove();

      $("#vehicle_"+no).parent().children().each(function(){
        if(this.tagName=="SPAN")
        {
          this.remove();
        }
      });

      $("#variant_"+no).parent().children().each(function(){
        if(this.tagName=="SPAN")
        {
          this.remove();
        }
      });

      $("#color_"+no).parent().children().each(function(){
        if(this.tagName=="SPAN")
        {
          this.remove();
        }
      });

      prepareVehicle(no);
      prepareColor(no,0,true);
    }

    function remove_detail(no)
    {
      var vso_detail_id = $("#vso_detail_"+no).val();
      var vso_id = $("#vso-id").val();

      if(vso_detail_id!="")
      {
        window.location.href="{{url('/sales/vso/remove_detail/')}}/"+vso_id+"/"+vso_detail_id;
      }
      else
      {
        if(no>0)
        {
          $("#vehicle_group_"+no).remove();
        }
      }
    }

$("#review").on("shown.bs.modal",function(){
    if(!first)
    {
        first = true;
        $.get("{{url('/sales/review/')}}").done(function(data){
        $("#review_rank").text(data.rank);
        $("#review_total_vso").text(data.total_vso);
        $("#review_total_prospect").text(data.total_prospect);
        $("#review_new_vso").text(data.new_vso);
        $("#review_new_prospect").text(data.new_prospect);

        var c = new Chart(document.getElementById("canvas_statistic"), {
            type: "line",
            data: {
                labels: data.label,
                datasets: [{
                    label: "Prospect",
                    backgroundColor: "rgba(38, 185, 154, 0.31)",
                    borderColor: "rgba(38, 185, 154, 0.7)",
                    pointBorderColor: "rgba(38, 185, 154, 0.7)",
                    pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointBorderWidth: 1,
                    data: data.datasets[0]
                }, {
                    label: "VSO",
                    backgroundColor: "rgba(3, 88, 106, 0.3)",
                    borderColor: "rgba(3, 88, 106, 0.70)",
                    pointBorderColor: "rgba(3, 88, 106, 0.70)",
                    pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(151,187,205,1)",
                    pointBorderWidth: 1,
                    data: data.datasets[1]
                },{
                    label: "Target",
                    data: Array.apply(null, new Array(12)).map(Number.prototype.valueOf, 8),
                    fill: false,
                    radius: 0,
                    borderColor: "rgba(255, 0, 0, 0.70)",
                }]
            }
        });
    })
    }
})

function next()
{
    var vso_no = $("#vso_no").val();
    window.location.href="{{url('/sales/vso_dashboard/')}}/"+vso_no;
}

function scrollToAnchor(anchor_id){
    var tag = $("#"+anchor_id+"");
    $('html,body').animate({scrollTop: tag.offset().top},'slow');
}

function prepareColor(no, vid,firsttime=false,show=false)
{
    if(vid==0)
    {
        $("#color_"+no).empty();
        $("#color_"+no).parent().children().each(function(){
        if(this.tagName=="SPAN" || this.tagName=="DIV")
        {
            this.remove();
        }
        });
    var hh = "<option value='' data-class='cs-sub-head' selected>Select Color</option>";
    $("#color_"+no).append(hh);
    [].slice.call( document.querySelectorAll( "#color_"+no ) ).forEach( function(el) {
        new SelectFx(el,{
        stickyPlaceholder:false,
        onChange: function(val, txt){
            $("#hidden-color").val(val);
            update_prospect_detail(no);
        }
        });
        
    });
    return;
    }

    $.get("{{url('/inventory/getvariantdetail/').'/'}}"+vid,function(res){
    if(res)
    {
        var color_list = res.color.split(',');
    }
    else
    {
        var color_list = [""];
    }
    
    $("#color_"+no).empty();
    $("#color_"+no).parent().children().each(function(){
        if(this.tagName=="SPAN" || this.tagName=="DIV")
        {
        this.remove();
        }
    });
    var tmp_name = res.model +" "+ res.variant;
    var hh = "<option value='' data-class='cs-sub-head'>"+tmp_name+"</option>";
    $("#color_"+no).append(hh);
    for(var i in color_list)
    {
        var opt = $('<option>',{value:color_list[i],text:color_list[i]});
        $("#color_"+no).append(opt);
        var s_c = $("#hidden-color").val();
        if(s_c==color_list[i])
        {
        opt.attr('selected',true);
        }
        else if(i==0)
        {
        opt.attr('selected',true);
        }
    }

    [].slice.call( document.querySelectorAll( "#color_"+no ) ).forEach( function(el) {
        new SelectFx(el,{
        stickyPlaceholder:false,
        onChange: function(val, txt){
            $("#hidden-color").val(val);
            update_prospect_detail(no);
        }
        });
    });

    if(show)
    {
        $("#color_"+no).parent().find('.cs-placeholder')[0].toggleSelect();
    }

    });
}

function prepareVehicle(no)
{
    [].slice.call( document.querySelectorAll( '#vehicle_'+no ) ).forEach( function(el) {
    new SelectFx(el,{
        stickyPlaceholder:false,
        onChange: function(val, txt){
        $("#vehicle_id_"+no).val(val);
        update_prospect_detail(no);
        $("#variant_"+no).empty();

        $("#variant_"+no).parent().children().each(function(){
            if(this.tagName=="SPAN" || this.tagName=="DIV")
            {
            this.remove();
            }
        });

        $.get("{{url('/inventory/getvariant/').'/'}}"+val,function(data){
            if(data)
            {
            var tmp_name = data[0].brand + " " +data[0].model;
            var hh = "<option value='' data-class='cs-sub-head' selected>"+tmp_name+"</option>";

            $("#variant_"+no).append(hh);
            for(var i=0;i<data.length;i++)
            {
                $("#variant_"+no).append($('<option>',{value:data[i].id,text:data[i].text}));
            }
            }
            [].slice.call( document.querySelectorAll( '#variant_'+no ) ).forEach( function(el) {
            new SelectFx(el,{
                stickyPlaceholder:false,
                onChange: function(val,txt){
                update_prospect_detail(no);
                html = $("#vehicle_"+no).parent().find('.cs-placeholder').html() + " - " + txt;
                $("#vehicle_"+no).parent().find('.cs-placeholder').html(html);

                prepareColor(no, val,false,true);
                }
            });
            });

            $("#variant_"+no).parent().find('.cs-placeholder')[0].toggleSelect();
        });
        }
    });
    });


    [].slice.call( document.querySelectorAll( '#variant_'+no ) ).forEach( function(el) {
    new SelectFx(el,{
        stickyPlaceholder:false
    });
    });
    
}

$(function(){
    if($("#vso-id").val()>0)
    {
        $("#btndone").removeAttr('disabled');
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

        @if($details && $details->count()>0)
        @php
            $i=0
        @endphp
        @foreach($details as $d)
            @if($i==0)
            prepareVehicle({{$i}});
            @else
            addVehicle();
            @endif
            prepareColor({{$i}},0,true);
            $("#vehicle_{{$i}}").parent().find('.cs-placeholder').html("{{$d->model}}");
            $("#color_{{$i}}").parent().find('.cs-placeholder').html("{{$d->color}}");
            $("#variant_{{$i}}").val({{$d->variant_id}});
            $("#vehicle_id_{{$i}}").val({{$d->vehicle_id}});
            $("#vso_detail_{{$i}}").val({{$d->id}});
            
            @php
            $i++
            @endphp
        @endforeach
        @else
        prepareVehicle(0);
        prepareColor(0,0,true);
        @endif

        $(".cs-select").css('pointer-events','none');

    @if($vso)
        $("#contact").attr('readonly',true);
        $("#name").attr('readonly',true);
        $("#identity").attr('readonly',true);
        $("#company").attr('readonly',true);
        $("#companyname").attr('readonly',true);

        $(".cs-select").css('pointer-events','inherit');
        $("input[type=file]").attr('disabled',false);
    @endif

});

$("input").on('focus',function(){
this.select();
})

function check_type(vso_type,show)
    {
      if(vso_type && vso_type!="")
      {
        $("[data-brand]").attr('data-type',vso_type);
        $(".btn-vso-type").css('pointer-events','none');

        
        if(vso_type=="COMPANY COMMERCIAL"||vso_type=="COMPANY PRIVATE")
        {
          if(vso_type=="COMPANY PRIVATE")
          {
            $("#vso_type").val("COMPANY PRIVATE");
            $("#vso_type_2").addClass('active');
          }
          else if(vso_type=="COMPANY COMMERCIAL")
          {
            $("#vso_type").val("COMPANY COMMERCIAL");
            $("#vso_type_3").addClass('active');
          }

          $("#company").attr('disabled',false);
          $("#company").focus();
        }
        else
        {
          if(vso_type=="INDIVIDUAL")
          {
            $("#vso_type").val("INDIVIDUAL");
            $("#vso_type_1").addClass('active');
          }
          $("#divCompanyNo").fadeOut();
          $("#divCompanyName").fadeOut();
          
          $("#identity").attr('disabled',false);
          $("#identity").focus();
        }
      }
    }

    function verify()
    {
        $("#form-vso").validate({
            rules:{
                contact:{matches:"0[0-9]+",minlength:9, maxlength:11},
                identity:{matches:"[0-9]{6}-[0-9]{2}-[0-9]{4}",minlength:14,maxlength:14}
            },
            messages:{
                contact:"Please enter a valid phone number, sample 0125451300",
                identity:"Please enter a valid IC No, sample 880808-08-8888"
            },
            errorPlacement: function (error, element) {
                if (element.parent().is('.input-group'))
                   error.appendTo(element.parents(".form-group:first"));
                else
                   error.insertAfter(element);
             }
        });

        return $("#form-vso").valid();
    }

    $("input").on('input',function(){
        verify();
    })

    function check_identity_db()
    {
      $("#identity").attr('readonly',true);

      var identity = $("#identity").val();
      $("#table_customer tbody tr").remove();
      $("#pc_customer_type").val("");
      $("#pc_customer_id").val("");
      $("#pc_customer_name").val("");
      $("#pc_customer_contact").val("");
      $("#pc_customer_address_1").val("");
      $("#pc_customer_address_2").val("");
      $("#pc_customer_postcode").val("");
      $("#pc_customer_city").val("");
      $("#pc_customer_state").val("");
      $("#pc_customer_country").val("");

      
      var vso_type = $("#vso_type").val();

      $.get('{{url("/sales/check_identity/")}}/'+identity).done(function(data){
        if(data && data.length>0)
        {
          $("#table_customer tbody").empty();
          for(var i=0;i<data.length;i++)
          {
            $("#table_customer tbody").append('<tr class="clickable-row" data-unique="'+data[i].type+'_'+data[i].id+'" data-id="'+data[i].id+'" data-name="'+data[i].full_name+'" data-contact="'+data[i].contact+'" data-address_1="'+data[i].address_1+'" data-address_2="'+data[i].address_2+'" data-city="'+data[i].city+'" data-postcode="'+data[i].postcode+'" data-state="'+data[i].state+'" data-country="'+data[i].country+'"  onclick="select_customer(\''+data[i].type+'\','+data[i].id+')"><td>'+data[i].identity+'</td><td>'+data[i].full_name+'</td><td>'+data[i].contact+'</td><td>'+data[i].address_1+' '+data[i].address_2+' '+data[i].city+' '+data[i].postcode+' '+data[i].state+' '+data[i].country+' '+'</td></tr>');
          }
          select_customer(data[0].type,data[0].id);

          $("#check_customer").modal({backdrop:'static',keyboard:false});
        }
        else
        {
          if($("#customer-id").val()=="")
          {
            $("#contact").attr('disabled',false);
            $("#contact").focus();
          }
        }
      })
    }

    function select_customer(type,id)
    {
      $("#table_customer tbody tr").removeClass('row-selected');
      var data_unique = type+'_'+id;
      var row = $("#table_customer tbody tr[data-unique='"+data_unique+"']");
      row.addClass('row-selected');
      var data = row.data();
      $("#pc_customer_type").val(type);
      $("#pc_customer_id").val(id);
      $("#pc_customer_name").val(data.name.toUpperCase());
      $("#pc_customer_contact").val(data.contact);
      $("#pc_customer_address_1").val(data.address_1);
      $("#pc_customer_address_2").val(data.address_2);
      $("#pc_customer_postcode").val(data.postcode);
      $("#pc_customer_city").val(data.city);
      $("#pc_customer_state").val(data.state);
      $("#pc_customer_country").val(data.country);
    }

    function identity_helper(ctrl)
    {
        var ex = $(ctrl).val();
        if(ex.length>=7)
        {
            if(ex[6]!="-")
            {
                $(ctrl).val(ex.substring(0,6) + "-" + ex.substring(6));
            }
        }
        var ex = $(ctrl).val();
        if(ex.length>=10)
        {
            if(ex[9]!="-")
            {
                $(ctrl).val(ex.substring(0,9) + "-" + ex.substring(9));
            }
        }

        var ex = $(ctrl).val();
        if(ex.length>=14)
        {
            $(ctrl).val(ex.substring(0,14));
            $(ctrl).blur();
        }
    }

    function cancel_match_customer()
    {
      $("#customer-id").val('');
      $("#check_customer").modal('hide');
      $("#identity").val('');
      $("#identity").attr('readonly',false);
      $("#identity").focus();
    }

    function no_match_customer()
    {
      $("#customer-id").val('');
      $("#check_customer").modal('hide');
      $("#contact").attr('disabled',false);
      $("#contact").focus();
    }

    function match_customer()
    {
      var type = $("#pc_customer_type").val();
      var cid = $("#pc_customer_id").val();
      var name = $("#pc_customer_name").val();
      var full_name = $("#pc_customer_full_name").val();
      var contact = $("#pc_customer_contact").val();
      var address_1 = $("#pc_customer_address_1").val();
      var address_2 = $("#pc_customer_address_2").val();
      var postcode = $("#pc_customer_postcode").val();
      var city =$("#pc_customer_city").val();
      var state =$("#pc_customer_state").val();
      var country =$("#pc_customer_country").val();

      name = full_name!=""?full_name:name;

      //HY
      $("#customer-type").val(type);
      $("#customer-id").val(cid);
      $("#contact").val(contact);
      $("#contact").attr('disabled',false);
      
      $("#name").attr('disabled',false);
      $("#name").val(name.toUpperCase());

      $(".cs-select").css('pointer-events','inherit');

      $("#check_customer").modal('hide');

      var vso_id = $("#vso-id").val();
      if(vso_id!="")
      {
        update_vso();
      }
    }
</script>
@endpush