@extends('layouts.layout')

@push('stylesheets')

@endpush

@section('main_container')

<div class="page-breadcrumb">
  <div class="row">
      <div class="col-7 align-self-center">
          <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Good Morning {{Auth::user()->name}}!</h3>
          <div class="d-flex align-items-center">
              <nav aria-label="breadcrumb">
                  <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{url('/')}}"></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url('/company/profile')}}">Company</a>
                    </li>
                    @if($type=="EMPLOYEE")
                    <li class="breadcrumb-item"><a href="{{url('/company/employees')}}">Employee Mangement</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current)}}">Employee</a>
                    </li>
                    @else
                    <li class="breadcrumb-item"><a href="{{url('/company/salesmen')}}">Salesman Mangement</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Salesman</a>
                    </li>
                    @endif
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
            <form id="form-employee" method="POST" action="{{url('/company/employee/update')}}">
                {{Form::token()}}
                <input type="hidden" id="id" name="id" value="{{$item->id}}">
                <input type="hidden" name="employee_type" value="{{$type}}">
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Link User</label>
                            <select class="form-control" id="user_id" name="user_id" onchange="check_user()">
                                <option value="0"></option>
                                    @foreach($user_list as $u)
                                      <option value="{{$u->id}}">{{$u->name}} ({{$u->email}})</option>
                                    @endforeach
                                  </select>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nick Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$item->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{$item->full_name}}" required>
                        </div>
                        <div class="form-group">
                            <label>IC No</label>
                            <input type="text" class="form-control" id="identity" name="identity" value="{{$item->identity}}" required oninput="identity_helper(this)">
                        </div>
                        <div class="form-group">
                            <label>Branches <small>( Multi Selection )</small></label>
                            <select class="form-control" multiple name="branch_ids[]" onchange="set_supervisor(this)">
                                @foreach($branch_list as $b)
                                    <option value="{{$b->id}}" @if($b->selected) selected @endif>{{$b->branch_code}} - {{$b->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Upper Line</label>
                            <select class="form-control" id="upline_id" name="upline_id">
                                <option value="0"></option>
                                @foreach($upline_list as $u)
                                <option value="{{$u->id}}">{{$u->full_name}} ({{$u->email}})</option>
                                @endforeach
                            </select>
                        </div>
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
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{$item->email}}">
                        </div>
                        <div class="form-group">
                            <label>Contact No</label>
                            <input type="tel" class="form-control" id="contact" name="contact" value="{{$item->contact}}" required>
                        </div>
                        <div class="form-group">
                            <label>Phone (1)</label>
                            <input type="tel" class="form-control" id="contact_1" name="contact_1" value="{{$item->contact_1}}">
                        </div>
                        <div class="form-group">
                            <label>Phone (2)</label>
                            <input type="tel" class="form-control" id="contact_2" name="contact_2" value="{{$item->contact_2}}">
                        </div>
                        <div class="form-group">
                            <label>Join Date</label>
                            <input type="date" class="form-control" id="join_at" name="join_at" value="{{$item->join_at==""?Carbon\Carbon::today()->format('Y-m-d'):$item->join_at}}" required>
                        </div>
                        <div class="form-group">
                            <label>Left Date</label>
                            <input type="date" class="form-control" id="left_at" name="left_at" value="{{$item->left_at}}">
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
                                @if($type=="EMPLOYEE")
                                <button type="button" class="btn btn-default" onclick="window.location.href='{{url('/company/employees')}}'"><i class="fa fa-list"></i></button>
                                @else
                                <button type="button" class="btn btn-default" onclick="window.location.href='{{url('/company/salesmen')}}'"><i class="fa fa-list"></i></button>
                                @endif
                                <button type="button" class="btn btn-danger" onclick="deactivate()" @if($item->status!="ACTIVE") disabled @endif><i class="fa fa-ban"></i></button>
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
</div>

@push('modal')
<!-- Danger Alert Modal -->
<div id="modal-ban" class="modal fade" tabindex="-1" role="dialog"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="{{url('/company/employee/deactivate')}}">
                <div class="modal-header modal-colored-header bg-danger">
                    <h4 class="modal-title" id="modal-title">De-activate user</h4>
                </div>
                <div class="modal-body">
                        {{Form::token()}}
                        <input type="hidden" name="id" value="{{$item->id}}">
                        <input type="hidden" name="type" value="{{$type}}">
                    <div class="form-group">
                        <label>Confirm de-activate this user?</label>
                        <textarea class="form-control mid-text-area" name="description" placeholder="Please provide reason" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="modal-footer-left">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-footer-right">
                        <button type="submit" class="btn btn-danger"><i class="fa fa-ban"></i></button>
                    </div>
                </div>
            </form>
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

        $("#user_id").val('{{$item->user_id}}');
        $("#upline_id").val('{{$item->upline_id}}');

        $("#user_id").chosen();
        $("#upline_id").chosen();

        @if($item->status=="INACTIVE")
        $("input").attr('disabled','disabled');
        $(".btn-dark").attr('disabled','disabled');
        $(".btn-primary").attr('disabled','disabled');
        $(".btn-danger").attr('disabled','disabled');
        $("select").attr('disabled','disabled');
        $("textarea").attr('disabled','disabled');
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
        $("#form-employee").validate({
            rules:{
                contact_1:{matches:"0[0-9]+",minlength:9, maxlength:11},
                contact_2:{matches:"0[0-9]+",minlength:9, maxlength:11},
                contact:{matches:"0[0-9]+",minlength:9, maxlength:11},
                identity:{matches:"[0-9]{6}-[0-9]{2}-[0-9]{4}",minlength:14,maxlength:14}
            },
            messages:{
                contact:"Please enter a valid phone number, sample 0125451300",
                contact_1:"Please enter a valid phone number, sample 0125451300",
                contact_2:"Please enter a valid phone number, sample 0125451300",
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

function check_user()
  {
    var id = $("#user_id").val();
    var sid = $("#id").val();
    if(id!='')
    {
      var str = "";
      if(sid!="")
      {
        str = "?employee_id="+sid;
      }
      $.get('/company/check_user/USER/'+id+str,function(res){
        if(res.status=="OK")
        {
            $("#email").val(res.email);
            $("#email").prop('readonly',true);
          
            $("#name").val(res.name);
            $("#name").prop('readonly',true);

            $("#contact").val(res.contact);
            $("#contact").prop('readonly',true);
        }
        else
        {
          $("#email").prop('readonly',false);
          $("#name").prop('readonly',false);
          $("#contact").prop('readonly',false);
          $("#user_id").val("");
          $("#user_id").trigger("chosen:updated")
          new PNotify({nonblock: {nonblock: !0},
                text: 'Invalid Data',
                type: 'warning',
                styling: 'bootstrap3',
                hide:true,
                delay: 3000
            });
        }
      })
    }
  }

  function set_supervisor(ctrl)
  {
    var data = {'branch_ids':$(ctrl).val()};
    $.post('{{url("/company/get_upline")}}',data,function(res){
      $("#upline_id").empty();
      $("#upline_id").append("<option></option>");
      for(var i=0;i<res.length;i++)
      {
        var data = res[i];
        $("#upline_id").append("<option value='"+data.id+"'>"+data.name+" ("+data.email+")</option>");
      }

      @if($item)
      $("#upline_id").val("{{$item->upline_id}}");
      @endif

      $('#upline_id').trigger("chosen:updated"); 

      
    });
  }

  function deactivate()
  {
      $("#modal-ban").modal('show');
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
</script>
@endpush