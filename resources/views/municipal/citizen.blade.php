<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Mopani Hotspot Reporter</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="robots" content="all,follow">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@include('municipal_layouts.styles')
</head>
<body>
	@include('municipal_layouts.header')
	<div class="d-flex align-items-stretch">
		@include('municipal_layouts.sidebar')
		<div class="page-holder w-100 d-flex flex-wrap">
			<div class="container-fluid px-xl-5">
				<section class="py-5">
					<div class="row">
						@if (Session::has('success'))
							<div id="alert-msg" class="col-md-12 alert alert-success">
								<strong>{{ session('success') }}</strong>
							</div>
						@endif
						@if (Session::has('error'))
							<div id="alert-msg" class="col-md-12 alert alert-danger">
								<strong>{{ session('error') }}</strong>
							</div>
						@endif
						<div class="col-lg-12 mb-5">
							@if (empty($edit))
								<div class="card">
									<div class="card-header">
										<h3 class="h6 text-uppercase mb-0">
											Create Citizen Account
											<a href="{{ url('municipal/manage/citizens') }}" class="float-right btn btn-sm btn-rounded btn-info">Manage Citizens</a>
										</h3>
									</div>
									<div class="card-body">
										<form method="POST" id="citizen-form" enctype="multipart/form-data">
											@csrf
											<div class="row">
												<div class="col-md-4 form-group {{ ($errors->has('municipal_id')) ? 'has-error' : '' }}">
													<label class="form-control-label">Municipal</label>
													<select name="municipal_id" id="municipal_id" class="form-control">
														<option value="">--Select Municipal--</option>
														<option value="{{ $municipal->id }}" {{ (old('municipal_id')) == $municipal->id ? 'selected' : '' }}>{{ $municipal->name }}</option>
													</select>
													@if ($errors->has('municipal_id'))
														<strong class="help-block">{{ $errors->first('municipal_id') }}</strong>
													@endif
												</div>
												<div class="col-md-4 form-group {{ ($errors->has('fullname')) ? 'has-error' : '' }}">
													<label class="form-control-label">Fullname</label>
													<input type="text" name="fullname" id="fullname" placeholder="Fullname" class="form-control" value="{{ old('fullname') }}">
													@if ($errors->has('fullname'))
														<strong class="help-block">{{ $errors->first('fullname') }}</strong>
													@endif
												</div>												
												<div class="col-md-4 form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
													<label class="form-control-label">Phone</label>
													<input type="number" name="phone" id="phone" placeholder="XXXXXXXXXX" class="form-control" min="0" value="{{ old('phone') }}">
													@if ($errors->has('phone'))
														<strong class="help-block">{{ $errors->first('phone') }}</strong>
													@endif
												</div>
											</div>
											<div class="row">												
												<div class="col-md-4 form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
													<label class="form-control-label">Email</label>
													<input type="email" name="email" id="email" placeholder="name@domain.com" class="form-control" value="{{ old('email') }}">
													@if ($errors->has('email'))
														<strong class="help-block">{{ $errors->first('email') }}</strong>
													@endif
												</div>
												<div class="col-md-4 form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
													<label class="form-control-label">Address</label>
													<input type="text" name="address" id="address" placeholder="Address" class="form-control" value="{{ old('address') }}">
													@if ($errors->has('address'))
														<strong class="help-block">{{ $errors->first('address') }}</strong>
													@endif
												</div>
												<div class="col-md-4 form-group {{ ($errors->has('picture')) ? 'has-error' : '' }}">
													<label class="form-control-label">Picture</label>
													<input type="file" name="picture" id="picture" placeholder="Picture" class="form-control" accept="image/*">
													@if ($errors->has('picture'))
														<strong class="help-block">{{ $errors->first('picture') }}</strong>
													@endif
												</div>
											</div>
											<div class="row">
												<div class="col-md-4 form-group">
													<label class="form-control-label">Default Password</label>
													<input type="text" name="password" id="password" placeholder="" class="form-control" value="123456" readonly>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4 form-group">
													<button id="create-btn" class="btn btn-primary">Create Account</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							@else
								<div class="card">
									<div class="card-header">
										<h3 class="h6 text-uppercase mb-0">
											Update Citizen Account
											<a href="{{ url('municipal/manage/citizens') }}" class="float-right btn btn-sm btn-rounded btn-info">Manage Citizens</a>
										</h3>
									</div>
									<div class="card-body">
										<form method="POST" id="update-citizen-form" enctype="multipart/form-data">
											@csrf
											<div class="row">
												<div class="col-md-4 form-group {{ ($errors->has('municipal_id')) ? 'has-error' : '' }}">
													<label class="form-control-label">Municipal</label>
													<select name="municipal_id" id="municipal_id" class="form-control">
														<option value="">--Select Municipal--</option>
														<option value="{{ $municipal->id }}" {{ ($edit->municipal_id) == $municipal->id ? 'selected' : '' }}>{{ $municipal->name }}</option>
													</select>
													@if ($errors->has('municipal_id'))
														<strong class="help-block">{{ $errors->first('municipal_id') }}</strong>
													@endif
												</div>
												<div class="col-md-4 form-group {{ ($errors->has('fullname')) ? 'has-error' : '' }}">
													<label class="form-control-label">Fullname</label>
													<input type="text" name="fullname" id="fullname" placeholder="Fullname" class="form-control" value="{{ $edit->fullname }}">
													@if ($errors->has('fullname'))
														<strong class="help-block">{{ $errors->first('fullname') }}</strong>
													@endif
												</div>												
												<div class="col-md-4 form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
													<label class="form-control-label">Phone</label>
													<input type="number" name="phone" id="phone" placeholder="XXXXXXXXXX" class="form-control" min="0" value="{{ $edit->phone }}">
													@if ($errors->has('phone'))
														<strong class="help-block">{{ $errors->first('phone') }}</strong>
													@endif
												</div>
											</div>
											<div class="row">												
												<div class="col-md-4 form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
													<label class="form-control-label">Email</label>
													<input type="email" name="email" id="email" placeholder="name@domain.com" class="form-control" value="{{ $edit->email }}">
													@if ($errors->has('email'))
														<strong class="help-block">{{ $errors->first('email') }}</strong>
													@endif
												</div>
												<div class="col-md-8 form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
													<label class="form-control-label">Address</label>
													<input type="text" name="address" id="address" placeholder="Address" class="form-control" value="{{ $edit->address }}">
													@if ($errors->has('address'))
														<strong class="help-block">{{ $errors->first('address') }}</strong>
													@endif
												</div>
											</div>
											<div class="row">
												<div class="col-md-4 form-group {{ ($errors->has('picture')) ? 'has-error' : '' }}">
													<label class="form-control-label">Picture</label>
													<input type="file" name="picture" id="picture" placeholder="Picture" class="form-control" accept="image/*">
													@if ($errors->has('picture'))
														<strong class="help-block">{{ $errors->first('picture') }}</strong>
													@endif
												</div>
											</div>
											<div class="row">
												<div class="col-md-4 form-group">
													<button id="update-btn" class="btn btn-primary">Update Account</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							@endif
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	@include('municipal_layouts.scripts')
	<script>

		// jquery email method
		jQuery.validator.addMethod("customemail", function(value, element) {
			return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
		}, "Please enter a valid email address.");

		// create form validator
		$('#citizen-form').validate({
			rules: {
				municipal_id: { required: true },
				fullname: { required: true },				
				phone: { required: true },
				email: {
					required: true,
					customemail: true
				},
				address: { required: true },
				picture: { required: true }
			},
			messages: {
				municipal_id: "Select municipal.",
				fullname: "Enter fullname.",				
				phone: "Enter phone number.",
				email: {
					required: "Enter email address."
				},
				address: "Enter address.",
				picture: "Select picture."
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

		// update form validator
		$('#update-citizen-form').validate({
			rules: {
				municipal_id: { required: true },
				fullname: { required: true },				
				phone: { required: true },
				email: {
					required: true,
					customemail: true
				},
				address: { required: true },
			},
			messages: {
				municipal_id: "Select municipal.",
				fullname: "Enter fullname.",				
				phone: "Enter phone number.",
				email: {
					required: "Enter email address."
				},
				address: "Enter address.",
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

		// alert handler
		window.setTimeout(function () {
			$("#alert-msg").fadeOut(400).remove(600);
		}, 4000);

		// btn handler
		$('body').on('submit', '#citizen-form', function(){
			$('#create-btn').prop('disabled', true);
			$('#create-btn').html('Creating...');
		});

		$('body').on('submit', '#update-citizen-form', function(){
			$('#update-btn').prop('disabled', true);
			$('#update-btn').html('Updating..');
		});
	</script>
</body>
</html>
