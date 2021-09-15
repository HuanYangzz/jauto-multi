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
                    <li class="breadcrumb-item"><a href="{{url('/company/profile')}}">Company</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url('/company/users')}}">User Mangement</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">User</a>
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
            <form id="form-user" method="POST" action="{{url('/company/user/update')}}">
                {{Form::token()}}
                <input type="hidden" name="id" value="{{$item->id}}">
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Link Employee/Salesman</label>
                            <select class="form-control" id="employee_id" name="employee_id" onchange="check_employee()">
                                <option value=""></option>
                                    @foreach($employee_list as $u)
                                      <option value="{{$u->id}}">{{$u->name}} ({{$u->email}})</option>
                                    @endforeach
                                  </select>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{$item->email}}" required @if($item->email!="") readonly @endif>
                        </div>
                        <div class="form-group">
                            <label>User Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$item->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>Contact No</label>
                            <input type="tel" class="form-control" id="contact" name="contact" value="{{$item->contact}}" required  @if($item->contact!="") readonly @endif>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <button class="btn btn-dark btn-block" type="button" onclick="prompt_change()" @if(!$item->id) disabled @endif>Change Password</button>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Roles <small>( Multi Selection )</small></label>
                            <select class="form-control long-text-area" multiple name="role_ids[]">
                                @foreach($role_list as $role)
                                    <option value="{{$role->name}}" @if($role->selected) selected @endif>{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Branch in Charge <small>( Multi Selection )</small></label>
                            <select class="form-control long-text-area" multiple name="branch_ids[]">
                                @foreach($branch_list as $branch)
                                    <option value="{{$branch->id}}" @if($branch->selected) selected @endif>{{$branch->branch_code." - ".$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Join Date</label>
                            <input type="date" class="form-control" id="join_at" name="join_at" value="{{$item->join_at==""?Carbon\Carbon::today()->format('Y-m-d'):$item->join_at}}" required>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control mid-text-area" name="remark">{{$item->remark}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12">
                        <div class="modal-footer">
                            <div class="modal-footer-left">
                                <button type="button" class="btn btn-default" onclick="window.location.href='{{url('/company/users')}}'"><i class="fa fa-list"></i></button>
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
<div id="modal-password" class="modal fade" tabindex="-1" role="dialog"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <form action="{{url('/update_password/'.$item)}}" method="POST">
        {{Form::token()}}
        <input type="hidden" id="id" name="id">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Change Password</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" value="" required onchange="check_password()">
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" value="" required onchange="check_password()">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="" required onchange="check_password()">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="modal-footer-left">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-footer-right">
                        <button type="submit" id="btn-reset-password" class="btn btn-primary" disabled><i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->


<!-- Danger Alert Modal -->
<div id="modal-ban" class="modal fade" tabindex="-1" role="dialog"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="{{url('/company/user/deactivate')}}">
                <div class="modal-header modal-colored-header bg-danger">
                    <h4 class="modal-title" id="modal-title">De-activate user</h4>
                </div>
                <div class="modal-body">
                        {{Form::token()}}
                        <input type="hidden" name="id" value="{{$item->id}}">
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
            $("#theme").chosen();
        })

        $("input").on('input',function(){
            verify();
        })

        function verify()
        {
            $("#form-user").validate({
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
    
            return $("#form-user").valid();
        }

    function check_password()
    {
        var current_password = $("#current_password").val();
        $.post("{{url('/check_password/'.$item)}}",{'current_password':current_password},function(res){
            if(res=="CORRECT")
            {
                $("#current_password").removeClass('error');
                $("#btn-reset-password").removeAttr('disabled');
            }
            else
            {
                $("#current_password").addClass('error');
                $("#btn-reset-password").attr('disabled','disabled');
            }

            if($("#new_password").val()!=$("#confirm_password").val())
            {
                $("#confirm_password").addClass('error');
                $("#btn-reset-password").attr('disabled','disabled');
            }
            else
            {
                $("#confirm_password").removeClass('error');
            }
        });
    }

    function prompt_change()
    {
        $("#modal-password").modal('show').on('shown.bs.modal',function(){
            $("#current_password").focus();
            $("#btn-reset-password").attr('disabled','disabled');
        });
    }
    
    $(function(){
        $("#employee_id").val("{{$item->employee_id}}");
        $("#employee_id").chosen();

        @if($message)
            new PNotify({nonblock: {nonblock: !0},
                text: '{{$message}}',
                type: 'success',
                
                styling: 'bootstrap3',
                hide:true,
                delay: 3000
            });
        @endif

        @if($item->status=="INACTIVE")
        $("input").attr('disabled','disabled');
        $(".btn-dark").attr('disabled','disabled');
        $(".btn-primary").attr('disabled','disabled');
        $(".btn-ban").attr('disabled','disabled');
        $("select").attr('disabled','disabled');
        $("textarea").attr('disabled','disabled');
        @endif
    })
    
    function check_employee()
    {
        var id = $("#employee_id").val()
        var sid = $("#id").val();
        if(id!='')
        {
            var str = "";
            if(sid!="")
            {
                str = "?user_id="+sid;
            }
            $.get('/company/check_user/EMPLOYEE/'+id+str,function(res){
                if(res.status=="OK")
                {
                if(res.email!="")
                {
                    $("#email").val(res.email);
                    $("#email").prop('readonly',true);
                }
                
                $("#name").val(res.name);
                $("#name").prop('readonly',true);

                if(res.contact!="")
                {
                    $("#contact").val(res.contact);
                    $("#contact").prop('readonly',true);
                }
                }
                else
                {
                $("#email").prop('readonly',false);
                $("#name").prop('readonly',false);
                $("#employee_id").val("");
                $("#employee_id").trigger('chosen:updated');
                new PNotify({
                        nonblock: {nonblock: !0},
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
    
    function deactivate()
    {
        $("#modal-ban").modal('show');
    }
	</script>
@endpush