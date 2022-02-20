<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mopani Hotspot Reporter</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Google fonts - Popppins for copy-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,800">
    <!-- orion icons-->
    <link rel="stylesheet" href="{{ asset('css/orionicons.css') }}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{ asset('css/style.default.css') }}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ asset('img/favicon.png?3') }}">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
	<style>
		.help-block {
			color: #dd4b39;
		}
	</style>
  </head>
  <body>
    <div class="page-holder d-flex align-items-center">
		<div class="container">
			<div class="row align-items-center py-5">
				<div class="col-sm-12 col-lg-5 col-md-5 offset-md-4 px-lg-4">
					<h1 class="text-base text-primary text-uppercase mb-4">Mopani Hotspot Reporter</h1>
					<h2 class="mb-4">Welcome back!</h2>
					@if(Session::has('error'))
						<div id="alert-msg" class="alert alert-danger">
							<strong>{{ session('error') }}</strong>
						</div>
					@endif
					<form method="POST" id="loginForm" class="mt-4">
						@csrf
						<div class="form-group mb-4 {{ ($errors->has('email')) ? 'has-error' : '' }}">
							<input type="email" name="email" id="email" placeholder="Email address" class="form-control border-0 shadow form-control-lg" value="{{ old('email') }}">
						</div>
						<div class="form-group mb-4 {{ ($errors->has('password')) ? 'has-error' : '' }}">
							<input type="password" name="password" id="password" placeholder="Password" class="form-control border-0 shadow form-control-lg text-violet">
						</div>
						<button id="login-btn" type="submit" class="btn btn-primary shadow px-5">Log in</button>
					</form>
				</div>
			</div>
		</div>
    </div>
    <!-- JavaScript files-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/popper.js/umd/popper.min.js') }}"> </script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery.cookie/jquery.cookie.js') }}"> </script>
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
	<script src="{{ asset('js/front.js') }}"></script>
	<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
	<script>

		// jquery email method
		jQuery.validator.addMethod("customemail", function(value, element) {
			return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
		}, "Please enter a valid email address.");

		// form validator
		$('#loginForm').validate({
			rules: {
				email: {
					required:true,
					customemail: true
				},
				password: { required:true },
			},
			messages: {
				email: {
					required: "Enter email address."
				},
				password: "Enter password."
			},
			errorClass: "help-block",
			errorElement: "strong",
			onfocus:true,
			onblur:true,
			highlight:function(element){
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			unhighlight:function(element){
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
			},
			errorPlacement:function(error, element){
				if(element.parent('.input-group').length)
				{
					error.insertAfter(element.parent());
					return false;
				}
				else
				{
					error.insertAfter(element);
					return false;
				}
			}
		});

		// btn handler
		$('body').on('submit', '#loginForm', function(){
			$('#login-btn').prop('disabled', true);
			$('#login-btn').html('Logging in...');
		});

		// alert handler
		window.setTimeout(function () {
			$("#alert-msg").fadeOut(400).remove(600);
		}, 4000);
	</script>
  </body>
</html>
