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
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Sales</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url('/sales/customer_book')}}">Customer Contact Book</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Customer Detail</a>
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
            <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                <li class="nav-item">
                    <a href="#profile" data-toggle="tab" aria-expanded="true"
                        class="nav-link rounded-0 active">
                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                        <span class="d-lg-block">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#history" data-toggle="tab" aria-expanded="false"
                        class="nav-link rounded-0">
                        <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                        <span class="d-lg-block">VSO</span>
                    </a>
                </li>
            </ul>
            <div class="ln_solid"></div>
            <div class="tab-content">
                <div class="tab-pane show active" id="profile">
                    <form id="form-customer" method="POST" action="{{url('/sales/customer/save')}}">
                        {{Form::token()}}
                        <input type="hidden" id="id" name="id" value="{{$item->id}}">
                        <input type="hidden" id="status" name="status" value="{{$item->status}}">
                        <input type="hidden" id="customer_id" name="customer_id" value="{{$item->customer_id}}">
                        <div class="row">
                            <div class="col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Code</label>
                                    <input type="text" class="form-control" id="code" name="code" value="{{$item->code}}" placeholder="AUTO GENERATE" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" value="{{$item->full_name}}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="customer_type">Customer Type<span class="required">*</span>
                                    </label>
                                    <select class="form-control" id="customer_type" name="customer_type" required="" onchange="type_change(this)">
                                        <option value="" style="display: none"></option>
                                        <option value="INDIVIDUAL">INDIVIDUAL</option>
                                        <option value="COMPANY">COMPANY</option>
                                    </select>
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
                                        <button type="button" class="btn btn-default" onclick="window.location.href='{{url('/sales/customer_book')}}'"><i class="fa fa-list"></i></button>
                                    </div>
                                    <div class="modal-footer-right">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="history">
                    <div class="table-responsive">
                        <table id="datatable-responsive-2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Status</th>
                              <th>VSO No</th>
                              <th>Vehicle Model</th>
                              <th>Selling Price</th>
                              <th>Salesman</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($history as $vso)
                              <tr class="clickable-row" onclick="window.location.href='/sales/vso/{{$item->code}}/{{$vso->code}}'">
                                    <td class="v-top vso_{{strtolower($vso->status)}}">{{$vso->status}} <br/> {{$vso->duration}}</td>
                                    <td class="v-top">{{$vso->vso_no}} <br/>{{$vso->vso_type}}</td>
                                    <td class="v-top">{{$vso->vehicle}}<br/>{{$vso->color}}</td>
                                    <td class="v-top text-right">{{number_format($vso->total_price,2)}}</td>
                                    <td class="v-top">{{$vso->dealer}} <br/> {{$vso->dealer_contact}}</td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="modal-footer">
                                <div class="modal-footer-left">
                                    <button type="button" class="btn btn-default" onclick="window.location.href='{{url('/sales/customer_book')}}'"><i class="fa fa-list"></i></button>
                                </div>
                                <div class="modal-footer-right">
                                    <button type="button" class='btn btn-default pull-right' onclick="window.location.href='{{url("/sales/vso/".$item->code)}}';"><i class="fa fa-plus fa-lg"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

@push('modal')

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
        $("#form-customer").validate({
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

        @if($item->customer_type != '')
          $("#customer_type").val("{{$item->customer_type}}");
        @endif
        $("#customer_type").chosen();

        $("#datatable-responsive-2").dataTable({"lengthChange": false});

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