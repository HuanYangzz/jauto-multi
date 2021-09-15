@extends('layouts.layout')

@push('stylesheets')
<style>
    .v-variant .cs-placeholder{
        display: none !important;
      }

    .vehicle-group{
        display: contents;
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
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Presales</a>
                    </li>
                      <li class="breadcrumb-item"><a href="{{url()->current()}}">Prospect Form</a>
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
    <form id="form-prospect" method="POST" action="#" onsubmit="return false">
        <input type="hidden" id="history-id" value="{{$history?$history->id:""}}">
          <input type="hidden" id="prospect-id" value="{{$prospect?$prospect->id:""}}">
          <input type="hidden" id="hidden-color" value="{{$history?$history->color:""}}">
        <div class="card">
            <div class="card-header">
                <h4>Prospect Form</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-5">
                        <div class="form-group">
                            <label>Contact No</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="contact" name="contact" value="{{$prospect?$prospect->contact:''}}" required  @if($prospect&&$prospect->contact!="") readonly @endif>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="$('#contact').trigger('change')" @if($prospect&&$prospect->contact!="") disabled @endif><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <div class="input-group">
                                <input type="text" id="name" class="form-control" value="{{$prospect?($prospect->full_name!=''?$prospect->full_name:$prospect->name):""}}" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="$('#name').trigger('change')" @if($prospect&&$prospect->contact!="") disabled @endif><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="event">Where you meet the client?</label>
                            <div class="block">
                                <select class="cs-select cs-skin-boxes" name="event" id="event">
                                    <option value="" data-class='cs-sub-head' selected>Select Event</option>
                                    @foreach($event as $e)
                                        <option value='{{$e->id}}'>{{$e->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    @hasanyrole("System Admin|Manager|Branch Manager|Admin")
                    <div class="col-xs-none col-sm-1"></div>
                    <div class="col-xs-12 col-sm-5">
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" class="form-control" id="code" value="{{$history?$history->code:''}}" placeholder="AUTO GENERATE" readonly>
                        </div>
                        <div class="form-group">
                            <label>Created By</label>
                            <input type="text" class="form-control" id="created_by" value="{{$history?$history->created_by:''}}" placeholder="AUTO GENERATE" readonly>
                        </div>
                        <div class="form-group">
                            <label>Created At</label>
                            <input type="datetime" class="form-control" id="created_at" value="{{$history?$history->created_at:''}}" placeholder="AUTO GENERATE" readonly>
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
                        <input type="hidden" id="prospect_detail_0" value=""/>
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
                <div class="col-xs-12 col-sm-5">
                    <div class="form-group">
                        <label class="control-label" for="">Remarks <span class="required"></span>
                        </label>
                        <textarea id="description" name="description" class="form-control long-text-area" disabled onchange="update_prospect_history()" value="{{$history?$history->remark:''}}">{{$history?$history->remark:''}}</textarea>
                    </div>
                </div>
                <div class="col-xs-none col-sm-1"></div>
                <div class="col-xs-12 col-sm-5">
                    @include('components.file_upload_component',array('param'=>[
                        'item'=>$history,
                        'name'=>"prospect",
                        'label'=>'Upload supporting document or images',
                        'type'=>"prospect",
                        'verify'=>false,
                        'first'=>true,
                        'file_url'=>'',
                        'id'=>0,
                        'class'=>'',
                        'attr_name'=>"prospect",
                        'disabled'=>true,
                        "multiple"=>true,
                        'target-id'=>$history?$history->id:0,
                        'reload'=>false
                        ]))
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="modal-footer-left">
                <button type="button" class='btn btn-list' onclick="window.location.href='{{url('/presales/prospect_book')}}'"><i class="fa fa-list fa-lg"></i></button>
            </div>
            <div class="modal-footer-right">
                <button type="button" id='btndone' class='btn btn-primary' data-toggle="modal" data-target="#review" disabled><i class="fa fa-save fa-lg"></i></button>
            </div>
        </div>
    </div>
</div>

@push('modal')
<div class="modal fade" id="check_contact" role="dialog">
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
              <table class="table table-bordered text-center" id="table_contact">
                <thead>
                  <tr>
                    <td>Name</td>
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
                <button type="button" class="btn btn-default pull-left" onclick="cancel_match_contact()"><i class="fa fa-times fa-lg"></i></button>
            </div>
            <div class="modal-footer-right">
                <button type="button" class="btn btn-outline-dark" onclick="no_match_contact()">No - Create New Contact</button>
                <button type="button" class="btn btn-outline-primary" onclick="match_contact()">Yes - This is the person</button>
            </div>
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
                <button type="button" class="btn btn-outline-dark" onclick="home()"><i class="fa fa-list fa-lg"></i></button>
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
	var dirty = false;
    var first = false;

    function addVehicle()
    {
      var $vcdiv = $('div[id^="vehicle_group_"]:last');
      var no = parseInt($vcdiv.prop("id").match(/\d+/g),10)+1;

      if($("#prospect_detail_"+(no-1)).val()=="")
      {
        return;
      }

      var $vcclone = $vcdiv.clone().prop('id','vehicle_group_'+no);
      var $removelink = $vcclone.find('.btn-remove');

      $removelink.attr('onclick','remove_detail('+no+')');

      $vcdiv.after($vcclone);

      var $vhidden = $vcclone.find('input[id^="prospect_detail_"]:first');
      $vhidden.prop('id','prospect_detail_'+no);
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
      var prospect_detail_id = $("#prospect_detail_"+no).val();
      var history_id = $("#history-id").val();

      if(prospect_detail_id!="")
      {
        window.location.href="{{url('/presales/prospect/remove_detail/')}}/"+history_id+"/"+prospect_detail_id;
      }
      else
      {
        if(no>0)
        {
          $("#vehicle_group_"+no).remove();
        }
      }
    }

    [].slice.call( document.querySelectorAll( '#event' ) ).forEach( function(el) {
        new SelectFx(el,{
          stickyPlaceholder:false,
          onChange: function(val, txt){
            var pid = $("#prospect-id").val();
            if(pid!="")
            {
              update_prospect_history();
            } 
            else
            {
              var name = $("#name").val();
              if(name=='')
              {
                $("#name").focus();
              }
              else
              {
                $("#contact").focus();
              }
            }
          }
        });
      });

    $("#review").on("shown.bs.modal",function(){
      if(!first)
      {
        first = true;
        $.get("{{url('/presales/review/')}}").done(function(data){
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

    function home()
    {
      window.location.href="{{url('/presales/prospect_book')}}";
    }

    function next()
    {
      window.location.href="{{url('/presales/prospect')}}";
    }


    
    function select_contact(type,id)
    {
      $("#table_contact tbody tr").removeClass('row-selected');
      var data_unique = type+'_'+id;
      var row = $("#table_contact tbody tr[data-unique='"+data_unique+"']");
      row.addClass('row-selected');
      var data = row.data();
      $("#pc_customer_type").val(type);
      $("#pc_customer_id").val(id);
      $("#pc_customer_name").val(data.name);
      $("#pc_customer_full_name").val(data.full_name);
      $("#pc_customer_contact").val(data.contact);
      $("#pc_customer_address_1").val(data.address_1);
      $("#pc_customer_address_2").val(data.address_2);
      $("#pc_customer_postcode").val(data.postcode);
      $("#pc_customer_city").val(data.city);
      $("#pc_customer_state").val(data.state);
      $("#pc_customer_country").val(data.country);
    }

    
    
    
    function no_match_contact()
    {
      $("#check_contact").modal('hide');
      if($("#match_type").val()=="contact")
      {
        $("#name").attr("disabled",false);
        $("#name").focus();
      }
      else
      {
        new_prospect();
      }
    }

    function match_contact()
    {
      var name = $("#pc_customer_name").val();
      var full_name = $("#pc_customer_full_name").val();
      var contact = $("#pc_customer_contact").val();
      var address_1 = $("#pc_customer_address_1").val();
      var address_2 = $("#pc_customer_address_2").val();
      var postcode = $("#pc_customer_postcode").val();
      var city =$("#pc_customer_city").val();
      var state =$("#pc_customer_state").val();
      var country =$("#pc_customer_country").val();

      var ctype = $("#pc_customer_type").val();
      var id = $("#pc_customer_id").val();
      if(ctype=="CUSTOMER")
      {
        $("#customer-id").val(id);
      }
      else
      {
        $("#prospect-id").val(id);
      }
      $("#history-id").val('');

      if($("#match_type").val()=="contact")
      {
        $("#contact").val(contact);
        $("#name").val(full_name!=""?full_name:name);
      }
      else
      {
        $("#name").val(full_name!=""?full_name:name);
        if($("#contact").val()!=contact)
        {
          new_prospect();
          $("#check_contact").modal('hide');
          return;
        }
      }
      
      $("#description").attr('disabled',false);
      $(".cs-select").css('pointer-events','inherit');
      $("input[type=file]").attr('disabled',false);
      $("#check_contact").modal('hide');
    }

    
    function cancel_match_contact()
    {
      $("#customer-id").val('');
      $("#prospect-id").val('');
      $("#history-id").val('');
      $("#check_contact").modal('hide');
      
      if($("#match_type").val()=="contact")
      {
        $("#contact").val('');
        $("#contact").select(); 
      }
      else
      {
        $("#name").val('');
        $("#name").select(); 
      }
    }
    
    function review()
    {
      $.get("{{url('/presales/review/')}}").done(function(data){
        $("#review_rank").text(data.rank);
        $("#review_total_vso").text(data.total_vso);
        $("#review_total_prospect").text(data.total_prospect);
        $("#review_new_vso").text(data.new_vso);
        $("#review_new_prospect").text(data.new_prospect);
      });
    }

    
    function scrollToAnchor(anchor_id){
        var tag = $("#"+anchor_id+"");
        $('html,body').animate({scrollTop: tag.offset().top},'slow');
    }

    $("#contact").on('change',function(){
      InputContact(this.value);
    });

    $("#name").on('change',function(){
        InputName(this.value);
    });

    function verify()
    {
        $("#form-prospect").validate({
            rules:{
                contact:{matches:"0[0-9]+",minlength:9, maxlength:11}
            },
            messages:{
                contact:"Please enter a valid phone number, sample 0125451300"
            },
            errorPlacement: function (error, element) {
                if (element.parent().is('.input-group'))
                   error.appendTo(element.parents(".form-group:first"));
                else
                   error.insertAfter(element);
             }
        });

        return $("#form-prospect").valid();
    }
    
    function InputContact(contact)
    {
      $("#warn-contact").hide();
      var name = $("#name").val();

      if(contact=='')
      {
        $("#contact").focus();
      }
      else
      {
        if(verify())
        {
          $("#table_contact tbody tr").remove();

          $.get('{{url("/presales/check_contact/")}}/'+contact).done(function(data){
            $("#table_contact tbody").empty();
            if(data && data.length > 0)
            {
              for(var i=0;i<data.length;i++)
              {
                $("#table_contact tbody").append('<tr class="clickable-row" data-unique="'
                    +data[i].type+'_'+data[i].id+'" data-id="'+data[i].id+'" data-full_name="'
                    +data[i].full_name+'" data-name="'+data[i].name+'" data-contact="'
                    +data[i].search_contact+'" data-address_1="'+data[i].address_1+'" data-address_2="'
                    +data[i].address_2+'" data-city="'+data[i].city+'" data-postcode="'
                    +data[i].postcode+'" data-state="'+data[i].state+'" data-country="'+data[i].country
                    +'"  onclick="select_contact(\''+data[i].type+'\',' +data[i].id+')"><td class="text-left">'
                    +(data[i].name?data[i].name:'')+'</td><td class="text-left">'+(data[i].full_name?data[i].full_name:'')+'</td><td class="text-left">'
                    +data[i].search_contact+'</td><td class="text-left">'+(data[i].address_1?data[i].address_1:"")+' '
                    +(data[i].address_2?data[i].address_2:"")+' '+(data[i].city?data[i].city:"")+' '+(data[i].postcode?data[i].postcode:"")+' '
                    +(data[i].state?data[i].state:"")+' '+(data[i].country?data[i].country:"")+' '+'</td></tr>');
              }
              select_contact(data[0].type,data[0].id);
              $("#match_type").val('contact');
              $("#check_contact").modal({backdrop:'static',keyboard:false});
            }
            else
            {
              $("#name").attr('disabled',false);
              $("#name").focus();

              var prospect_id = $("#prospect-id").val();
              var name = $("#name").val();
              if(prospect_id!="")
              {
                new_prospect();
              }
            }
          });
        }
      }
    }

    function InputName(name)
    {
      if(!dirty)
      {
        return;
      }
      dirty = false;

      if($.trim(name)!='')
      {
        $.get('{{url("/presales/check_name/")}}/'+name).done(function(data){
          $("#table_contact tbody").empty();
          if(data && data.length > 0)
            {
              for(var i=0;i<data.length;i++)
              {
                $("#table_contact tbody").append('<tr class="clickable-row" data-unique="'+data[i].type+'_'+data[i].id+'" data-id="'+data[i].id+'" data-full_name="'+data[i].full_name+'" data-name="'+data[i].name+'" data-contact="'+data[i].contact+'" data-address_1="'+data[i].address_1+'" data-address_2="'+data[i].address_2+'" data-city="'+data[i].city+'" data-postcode="'+data[i].postcode+'" data-state="'+data[i].state+'" data-country="'+data[i].country+'"  onclick="select_contact(\''+data[i].type+'\','+data[i].id+')"><td>'+data[i].name+'</td><td>'+data[i].full_name+'</td><td>'+data[i].contact+'</td><td>'+data[i].address_1+' '+data[i].address_2+' '+data[i].city+' '+data[i].postcode+' '+data[i].state+' '+data[i].country+' '+'</td></tr>');
              }
              select_contact(data[0].type,data[0].id);
              $("#match_type").val('name');
              $("#check_contact").modal({backdrop:'static',keyboard:false});
              return false;
            }
            else
            {
              $("#name").attr('disabled',false);
              $("#name").focus();

              var prospect_id = $("#prospect-id").val();
              var name = $("#name").val();
              if(prospect_id!="")
              {
                new_prospect();
              }
            }

            name = name.toUpperCase();
            $("#name").val(name);

            new_prospect();

            $(".cs-select").css('pointer-events','inherit');
            $("input[type=file]").attr('disabled',false);
            $("#event").parent().find('.cs-placeholder')[0].toggleSelect();
        });
      }
      else
      {
        $("#name").focus();
      }
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

    function update_prospect_history()
    {
      var pid = $("#prospect-id").val();
      var history = $("#history-id").val();
      var evt = $("#event").val();
      var remark = $("#description").val();

      $.post("{{url('/presales/update_prospect_history')}}",{event:evt,prospect:pid,history:history,remark:remark})
        .done(function(data){
          $("#history-id").val(data.history);
          $("#code").val(data.item.code);
          $("#created_by").val(data.item.created_by);
          $("#created_at").val(data.item.created_at);

          $("#contact").attr('readonly',true);
          $("#name").attr('readonly',true);

          $(".hidden-upload-target-id").val(data.history);
          
          
          $("#description").attr('disabled',false);
          new PNotify({nonblock: {nonblock: !0},
            text: 'Data Saved',
            type: 'success',
            
            styling: 'bootstrap3',
            hide:true,
            delay: 3000
          });
      });
    }

    function update_prospect_detail(no)
    {
      var prospect_id = $("#prospect-id").val();
      var history_id = $("#history-id").val();
      var detail_id = $("#prospect_detail_"+no).val();
      var vehicle_id = $("#vehicle_id_"+no).val();
      var variant_id = $("#variant_"+no).val();
      var color = $("#color_"+no).val();
      var data = {
        "prospect_id":prospect_id,
        "history_id":history_id,
        "detail_id":detail_id,
        "vehicle_id":vehicle_id,
        "variant_id":variant_id,
        "color":color,
      }

      $.post("{{url('/presales/update_prospect_detail')}}",data,function(res){
        var id = res.detail_id;
        var hid = res.history;
        $("#prospect_detail_"+no).val(id);
        $("#history-id").val(hid);
        $(".hidden-upload-target-id").val(hid);

        $("#btndone").removeAttr('disabled');
        
        new PNotify({nonblock: {nonblock: !0},
          text: 'Data Saved',
          type: 'success',
          
          styling: 'bootstrap3',
          hide:true,
          delay: 3000
        });
      });
    }

    function new_prospect()
    {
      var name = $("#name").val();
      var contact = $("#contact").val();

      var data = {name:name,contact:contact};

      $.post("{{url('/presales/new_prospect')}}",data,function(res){
        var id = res.id;

        $("#prospect-id").val(id);
        $("#history-id").val('');

        $("#name").attr('disabled',false);
      $("#description").attr('disabled',false);
      $(".cs-select").css('pointer-events','inherit');
      $("input[type=file]").attr('disabled',false);

        new PNotify({nonblock: {nonblock: !0},
          text: 'Data Saved',
          type: 'success',
          
          styling: 'bootstrap3',
          hide:true,
          delay: 3000
        });
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

      $("#name").on('keydown',function(e){
        if(e.keyCode == 13)
        {
          $(this).blur();
        }
        else
        {
          dirty = true;
        }
      })

      if($("#history-id").val()>0)
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
            $("#prospect_detail_{{$i}}").val({{$d->id}});
            
            @php
              $i++
            @endphp
          @endforeach
        @else
          prepareVehicle(0);
          prepareColor(0,0,true);
        @endif

        $(".cs-select").css('pointer-events','none');

      @if($history)
        $("#contact").attr('readonly',true);
          $("#name").attr('readonly',true);
        $("#description").attr('disabled',false);
        $(".cs-select").css('pointer-events','inherit');
        $("input[type=file]").attr('disabled',false);
        
        @if($history->event!="")
          $("#event").parent().find('.cs-placeholder').html("{{$history->event}}");
        @endif
      @endif

      @if($prospect)
      @php $f=0 @endphp
        @foreach($prospect->files as $file)
        var no = "";  
        @if($f>0)
          no = "_{{$f}}";
          addFile_prospect();
        @endif
          
          $("#hidden_prospect"+no).val('{{$file["id"]}}');
          $("#hidden_prospect"+no).parent().after('<div id="link_prospect'+no+'" class="thumb_preview openDialog clickable-row" data-toggle="modal" data-target="#myModal" data-id="{{$file["id"]}}" data-type="image"><img id="prospect_img'+no+'" class="thumb_thumb"></img></div>');
          $("#link_prospect"+no).data('img','{{$file['image']}}');
          $("#prospect_img"+no).attr('src','{{$file['image']}}');
          

          @php $f++ @endphp
        @endforeach
      @else
      $("#contact").focus();
      @endif

      
    });

    $("input").on('focus',function(){
      this.select();
    })
</script>
@endpush