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
                    <li class="breadcrumb-item"><a href="{{url()->current()}}">Profile</a>
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
            <form method="POST" action="{{url('/update_profile')}}">
                {{Form::token()}}
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" value="{{$item->email}}" readonly>
                        </div>
                        <div class="form-group">
                            <label>User Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$item->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <button class="btn btn-dark btn-block" type="button" onclick="prompt_change()">Change Password</button>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>UI Theme</label>
                            <select id="theme" class="form-control" name="theme">
                                @if(Cookie::get('theme')=="DARK")
                                <option value="LIGHT">LIGHT</option>
                                <option value="DARK" selected>DARK</option>
                                @else
                                <option value="LIGHT" selected>LIGHT</option>
                                <option value="DARK">DARK</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="row">

                    <div class="col-12">
                        <div class="form-actions">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary" onclick="return verify()"><i class="fa fa-save"></i></button>
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
    <form action="{{url('/update_password/'.Auth::user())}}" method="POST">
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
@endpush

@endsection

@push('scripts')
	<script>
        $(function(){
            $("#theme").chosen();
        })

    function verify()
    {

    }

    function check_password()
    {
        var current_password = $("#current_password").val();
        $.post("{{url('/check_password/'.Auth::user())}}",{'current_password':current_password},function(res){
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
	</script>
@endpush