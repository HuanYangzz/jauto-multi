@extends('layouts.layout')

@push('stylesheets')
<style>
.col-head{
    width:120px;
}
</style>
@endpush

@section('main_container')

<div class="page-breadcrumb">
  <div class="row">
      <div class="col-7 align-self-center">
          <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Good Morning {{Auth::user()->name}}!</h3>
          <div class="d-flex align-items-center">
              <nav aria-label="breadcrumb">
                  <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Presales</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url('/presales/prospect_book')}}">Prospect Book</a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Prospect Merge</a>
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
    <div class="card">
        <div class="card-body">
            <form id="form-prospect" method="POST" action="{{url('/presales/prospect/merge_data')}}">
                {{Form::token()}}
                <input type="hidden" id="id" name="id" value="">
                <input type="hidden" id="ex_id" name="ex_id" value="{{$item->id}}">
                <input type="hidden" id="ids" name="ids" value="{{$ids}}">
                <input type="hidden" id="status" name="status" value="ACTIVE">
                <input type="hidden" id="customer_id" name="customer_id" value="{{$item->customer_id}}">
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Nick Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$item->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{$item->full_name}}">
                        </div>
                        <div class="form-group">
                            <label>IC No</label>
                            <input type="text" class="form-control" id="identity" name="identity" value="{{$item->identity}}" oninput="identity_helper(this)">
                        </div>
                        <div class="form-group">
                            <label>Driving License No</label>
                            <input type="text" class="form-control" id="license_no" name="license_no" value="{{$item->license_no}}">
                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" class="form-control" id="company" name="company" value="{{$item->company}}">
                        </div>
                        <div class="form-group">
                            <label>Company Reg No</label>
                            <input type="text" class="form-control" id="company_reg_no" name="company_reg_no" value="{{$item->company_reg_no}}">
                        </div>
                        <div class="form-group">
                            <label>Job Title</label>
                            <input type="text" class="form-control" id="job_title" name="job_title" value="{{$item->job_title}}">
                        </div>
                        @hasanyrole('System Admin|Branch Manager|Manager|Admin')
                          <div class="form-group">
                            <label class="control-label" for="salesman_id">Salesman
                            </label>
                              <select class="form-control" id="salesman_id" name="salesman_id">
                                  <option value=""></option>
                                  @foreach($salesmen as $u)
                                    <option value="{{$u->id}}">{{$u->name}} ({{$u->full_name}})</option>
                                  @endforeach
                                </select>
                          </div>
                          @endhasanyrole
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Address 1</label>
                            <input type="text" class="form-control" id="address_1" name="address_1" value="{{$item->address_1}}">
                        </div>
                        <div class="form-group">
                            <label>Address 2</label>
                            <input type="text" class="form-control" id="address_2" name="address_2" value="{{$item->address_2}}">
                        </div>
                        <div class="form-group">
                            <label>Postcode</label>
                            <input type="number" class="form-control" id="postcode" name="postcode" value="{{$item->postcode}}" onchange="check_zip(this,'legal')" minlength="5" maxlength="5">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{$item->city}}">
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{$item->state}}">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="{{$item->country}}">
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Phone (1)</label>
                            <input type="tel" class="form-control" id="contact" name="contact" value="{{$item->contact}}" required>
                        </div>
                        <div class="form-group">
                            <label>Phone (2)</label>
                            <input type="tel" class="form-control" id="contact_1" name="contact_1" value="{{$item->contact_1}}">
                        </div>
                        <div class="form-group">
                            <label>Home Contact</label>
                            <input type="tel" class="form-control" id="home_contact" name="home_contact" value="{{$item->home_contact}}">
                        </div>
                        <div class="form-group">
                            <label>Office Contact</label>
                            <input type="tel" class="form-control" id="office_contact" name="office_contact" value="{{$item->office_contact}}">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{$item->email}}">
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control mid-text-area" name="remarks">{{$item->remarks}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="modal-footer">
                            <div class="modal-footer-left">
                                <button type="button" class="btn btn-default" onclick="window.location.href='{{url('/presales/prospect_book')}}'"><i class="fa fa-list"></i></button>
                            </div>
                            <div class="modal-footer-right">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Match List <small>( click to replace value )</small></h4>
                    <div class="table-responsive">
                        <table id="datatable-responsive-1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td class="col-head">Nick Name</td>
                                @foreach($list as $p)
                                <td>
                                <input class="form-control clickable-row" onclick="set_field(this,'Nick Name','name')" type="text" readonly value="{{$p->name}}" />
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="col-head">Full Name</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" onclick="set_field(this,'Full Name','full_name')" type="text" readonly value="{{$p->full_name}}" />
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">IC</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" onclick="set_field(this,'IC','identity')" type="text" readonly value="{{$p->identity}}" />
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">Driving License No</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" onclick="set_field(this,'Driving License No','license_no')" type="text" readonly value="{{$p->license_no}}" />
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">Company</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" onclick="set_field(this,'Company','company')" type="text" readonly value="{{$p->company}}" />
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">Company Reg No</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" onclick="set_field(this,'Company Reg No','company_reg_no')" type="text" readonly value="{{$p->company_reg_no}}" />
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">Job Title</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" onclick="set_field(this,'Job Title','job_title')" type="text" readonly value="{{$p->job_title}}" />
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">Salesman</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" type="text" readonly value="{{$p->salesman_str}}" onclick="set_salesman('{{$p->salesman_id}}','{{$p->salesman_str}}')" />
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">Address</td>
                                @foreach($list as $p)
                                <td class="mid-text-area">
                                    <textarea class="mid-text-area form-control clickable-row" type="text" readonly onclick="set_address('{{$p->address_1}}','{{$p->address_2}}','{{$p->postcode}}','{{$p->city}}','{{$p->state}}','{{$p->country}}','{{$p->address}}')">{{$p->address}}</textarea>
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">Phone No.(1)</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" onclick="set_field(this,'Phone No.(1)','contact')" type="text" readonly value="{{$p->contact}}" />
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">Phone No.(2)</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" onclick="set_field(this,'Phone No.(2)','contact_1')" type="text" readonly value="{{$p->contact_1}}" />
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">Home Contact</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" onclick="set_field(this,'Home Contact','home_contact')" type="text" readonly value="{{$p->contact_home}}" />
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">Office Contact</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" onclick="set_field(this,'Office Contact','office_contact')" type="text" readonly value="{{$p->contact_office}}" />
                                </td>
                                @endforeach
                                </tr>
                                <tr>
                                <td class="col-head">Email</td>
                                @foreach($list as $p)
                                <td>
                                    <input class="form-control clickable-row" onclick="set_field(this,'Email','email')" type="text" readonly value="{{$p->email}}" />
                                </td>
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('modal')
<div id="confirm-modal" class="modal fade" tabindex="-1" role="dialog"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                Replace Value
            </div>
            <div class="modal-body">
                <input type="hidden" id="hidden_field">
                <input type="hidden" id="hidden_value">
                <input type="hidden" id="hidden_address_1">
                <input type="hidden" id="hidden_address_2">
                <input type="hidden" id="hidden_postcode">
                <input type="hidden" id="hidden_city">
                <input type="hidden" id="hidden_state">
                <input type="hidden" id="hidden_country">

                <label>Replace <span id="field"></span> to <span id="value"></span></label>
            </div>
            <div class="modal-footer">
                <div class="modal-footer-left">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-footer-right">
                    <button type="button" class="btn btn-outline-primary" onclick="replace_field()" data-dismiss="modal"><i class="fa fa-edit"></i></button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endpush

@endsection

@push('scripts')
<script>
    $(function(){
        @if($message)
            new PNotify({nonblock: {nonblock: !0},
                text: '{{$message}}',
                type: 'success',
                
                styling: 'bootstrap3',
                hide:true,
                delay: 3000
            });
        @endif
    })

    function scrollToAnchor(anchor_id){
        var tag = $("#"+anchor_id+"");
        $('html,body').animate({scrollTop: tag.offset().top-120},'fast');
    }

    function set_address(address_1,address_2,postcode,city,state,country,address)
    {
        $("#hidden_field").val('address');
        $("#field").html('Address');
        $("#value").html("\""+ address+"\"");

        $("#hidden_address_1").val(address_1);
        $("#hidden_address_2").val(address_2);
        $("#hidden_postcode").val(postcode);
        $("#hidden_city").val(city);
        $("#hidden_state").val(state);
        $("#hidden_country").val(country);

        $("#confirm-modal").modal('show');
    }

    function set_salesman(value,name)
    {
        $("#hidden_field").val('salesman_id');
        $("#hidden_value").val(value);
        $("#field").html(field);
        $("#value").html("\""+ name+"\"");

        $("#confirm-modal").modal('show');
    }

    function set_field(ctrl,field,key)
    {
        var value = ctrl.value;
        $("#hidden_field").val(key);
        $("#hidden_value").val(value);

        $("#field").html(field);
        $("#value").html("\""+ value+"\"");

        $("#confirm-modal").modal('show');
    }

    function replace_field()
    {
        var field = $("#hidden_field").val();
        var value = $("#hidden_value").val();

        

        if(field=="address")
        {
            scrollToAnchor("address_1");
            $("#address_1").val($("#hidden_address_1").val());
            $("#address_2").val($("#hidden_address_2").val());
            $("#postcode").val($("#hidden_postcode").val());
            $("#city").val($("#hidden_city").val());
            $("#state").val($("#hidden_state").val());
            $("#country").val($("#hidden_country").val());
        }
        else if(field=="salesman_id")
        {
            scrollToAnchor("salesman_id_chosen");
            $("#"+field).val(value);
            $("#"+field).select();

            $("#salesman_id").trigger('chosen:updated');
        }
        else
        {
            scrollToAnchor(field);
            $("#"+field).val(value);
            $("#"+field).select();
        }
    }
    
    function check_zip(input)
    {
        $.get('/general/check_zip/'+input.value,function(data){
        if(data)
        {
            $("#city").val(data.city.toUpperCase());
            $("#state").val(data.state.toUpperCase());
            $("#country").val(data.country.toUpperCase());
        }
        });
    }
    
    function verify()
    {
        $("#form-prospect").validate({
            rules:{
                contact_1:{matches:"0[0-9]+",minlength:9, maxlength:11},
                home_contact:{matches:"0[0-9]+",minlength:9, maxlength:11},
                office_contact:{matches:"0[0-9]+",minlength:9, maxlength:11},
                contact:{matches:"0[0-9]+",minlength:9, maxlength:11},
                identity:{matches:"[0-9]{6}-[0-9]{2}-[0-9]{4}",minlength:14,maxlength:14}
            },
            messages:{
                contact:"Please enter a valid phone number, sample 0125451300",
                contact_1:"Please enter a valid phone number, sample 0125451300",
                home_contact:"Please enter a valid phone number, sample 0125451300",
                office_contact:"Please enter a valid phone number, sample 0125451300",
                identity:"Please enter a valid IC No, sample 880808-08-8888"
            },
            errorPlacement: function (error, element) {
                if (element.parent().is('.input-group'))
                   error.appendTo(element.parents(".form-group:first"));
                else
                   error.insertAfter(element);
             }
        });

        return $("#form-employee").valid();
    }

    $("input").on('input',function(){
        verify();
    })

    function show_website(type)
    {
        var url = $("#"+type).val();
        if(verify() && url != "")
        {
            window.open(url);
        }
    }

    $(function(){
        @if($item->salesman_id != '')
          $("#salesman_id").val("{{$item->salesman_id}}");
        @endif
        $("#salesman_id").chosen();
    })

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
</script>
@endpush