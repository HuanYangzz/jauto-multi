@extends('layouts.spm')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
    	<div class="col-md-12 col-sm-12 col-xs-12">
	        <div class="x_panel">
	          <div class="x_title"> 
	            <h2>Change Password</h2>
	            <div class="clearfix"></div>
	          </div>
	          <div class="x_content">
	          	<form id="upload_form" class="form-horizontal form-label-left" method="POST" action="{{ url('/change_password') }}">
          			{{ csrf_field() }}
          			<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email
		                </label>
		                <div class="col-md-6 col-sm-6 col-xs-12">
		                  <input type="email" id="email" name="email" class="form-control col-md-7 col-xs-12" value="{{$item->email}}" disabled="">
		                </div>
		            </div>  
		            <div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name
		                </label>
		                <div class="col-md-6 col-sm-6 col-xs-12">
		                  <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="{{$item->name}}" disabled="">
		                </div>
		            </div>
		            <div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="current_password">Current Password <span class="required">*</span>
		                </label>
		                <div class="col-md-6 col-sm-6 col-xs-12">
		                  <input type="password" id="current_password" name="current_password" class="form-control col-md-7 col-xs-12" value="" required="">
		                </div>
		            </div>  
		            <div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_password">New Password <span class="required">*</span>
		                </label>
		                <div class="col-md-6 col-sm-6 col-xs-12">
		                  <input type="password" id="new_password" name="new_password" class="form-control col-md-7 col-xs-12" value="" required="">
		                </div>
		            </div>  
		            <div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Confirm New Password <span class="required">*</span>
		                </label>
		                <div class="col-md-6 col-sm-6 col-xs-12">
		                  <input type="password" id="password" name="password" class="form-control col-md-7 col-xs-12" value="" required="">
		                </div>
		            </div> 
		            <div class="form-group">
		            	<div class="col-md-9 col-sm-9 col-xs-12">
			            	<button class="btn btn-success pull-right">Save</button>
			            </div>
		            </div> 
          		</form>
	          </div>
	      </div>
	  </div>
    </div>
    <!-- /page content -->
@endsection

@push('scripts')
<script>
	function check()
	{
		if($("#new_password").val() != $("#password").val())
		{
			$("#password").css('border-color','red');
			$("#password")[0].setCustomValidity("Password not matched!");
		}
		else
		{
			$("#password").css('border-color','#ccc');
			$("#password")[0].setCustomValidity("");
		}
	}

	$("#password").on('input',function(){
		check();
	});

	$("#new_password").on('input',function(){
		check();
	});	

	$(function(){
		@if($message)
		new PNotify({
            text: '{{$message}}',
            type: '{{$message_class}}',
            styling: 'bootstrap3',
            hide:true,
            delay: 3000
        });
        @endif
	});
</script>
@endpush