<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="{{asset('/manifest.json')}}">
    <link rel="apple-touch-icon" href="{{asset('/img/mini_logo.png')}}">
    <title>{{env("SITE_TITLE")}}</title>
    
    <!-- Bootstrap -->
    <link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset("css/font-awesome.min.css") }}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ asset("css/gentelella.min.css") }}" rel="stylesheet">
    <link href="{{ asset("css/custom.css") }}" rel="stylesheet">

</head>

<body class="login">
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
				{!! BootForm::open(['url' => url('/password/email'), 'method' => 'post']) !!}
                <div style="width: 100%;display: flex;background-color:rgba(255,255,255,0.6);padding: 15px">
                        <div style="width: 100%">
                            <img src="{{url('/')}}/img/logo.png" width="100%">
                        </div>
                    </div>
                    <br/><br/>

				{!! BootForm::email('email', 'Email', old('email'), ['placeholder' => 'Email']) !!}
					
				{!! BootForm::submit('Send Password Reset Link', ['class' => 'btn btn-default col-md-9']) !!}
	
				<div class="clearfix"></div>
	<br/>				
				<div class="separator">
					<p class="change_link">
						<a href="{{ url('/login') }}" class="to_register"> Back to Log in page </a>
					</p>
					<div class="clearfix"></div>
					<br />
					<br />
					<br />
                        
				</div>
					
                {!! BootForm::close() !!}
            </section>
        </div>
    </div>
</div>
</body>
</html>