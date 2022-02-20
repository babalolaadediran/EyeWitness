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
	@include('district_layouts.styles')
</head>
<body>
	@include('district_layouts.header')
	<div class="d-flex align-items-stretch">
		@include('district_layouts.sidebar')
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
											Create Municipal Head Account
											<a href="{{ url('district/manage/municipal/heads') }}" class="float-right btn btn-sm btn-rounded btn-info">Manage Municipal Heads</a>
										</h3>
									</div>
									<div class="card-body">
										<form method="POST" id="municipal-head-form" enctype="multipart/form-data">
											@csrf
											<div class="row">
												<div class="col-md-4 form-group {{ ($errors->has('municipal_id')) ? 'has-error' : '' }}">
													<label class="form-control-label">Municipal</label>
													<select name="municipal_id" id="municipal_id" class="form-control">
														<option value="">--Select Municipal--</option>
														@foreach ($municipals as $municipal)
															<option value="{{ $municipal->id }}" {{ (old('municipal_id')) == $municipal->id ? 'selected' : '' }}>{{ $municipal->name }}</option>
														@endforeach
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
												<div class="col-md-4 form-group {{ ($errors->has('gender')) ? 'has-error' : '' }}">
													<label class="form-control-label">Gender</label>
													<select name="gender" id="gender" class="form-control">
														<option value="">--Select Gender--</option>
														<option value="Male" {{ (old('gender')) == 'Male' ? 'selected' : '' }}>Male</option>
														<option value="Female" {{ (old('gender')) == 'Female' ? 'selected' : '' }}>Female</option>
													</select>
													@if ($errors->has('gender'))
														<strong class="help-block">{{ $errors->first('gender') }}</strong>
													@endif
												</div>
											</div>
											<div class="row">
												<div class="col-md-4 form-group {{ ($errors->has('dob')) ? 'has-error' : '' }}">
													<label class="form-control-label">Date Of Birth</label>
													<input type="text" name="dob" id="dob" placeholder="Date Of Birth" class="form-control datepicker" value="{{ old('dob') }}">
													@if ($errors->has('dob'))
														<strong class="help-block">{{ $errors->first('dob') }}</strong>
													@endif
												</div>
												<div class="col-md-4 form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
													<label class="form-control-label">Phone</label>
													<input type="number" name="phone" id="phone" placeholder="XXXXXXXXXX" class="form-control" min="0" value="{{ old('phone') }}">
													@if ($errors->has('phone'))
														<strong class="help-block">{{ $errors->first('phone') }}</strong>
													@endif
												</div>
												<div class="col-md-4 form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
													<label class="form-control-label">Email</label>
													<input type="email" name="email" id="email" placeholder="name@domain.com" class="form-control" value="{{ old('email') }}">
													@if ($errors->has('email'))
														<strong class="help-block">{{ $errors->first('email') }}</strong>
													@endif
												</div>
											</div>
											<div class="row">
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
											Update Municipal Head Account
											<a href="{{ url('district/manage/municipal/heads') }}" class="float-right btn btn-sm btn-rounded btn-info">Manage Municipal Heads</a>
										</h3>
									</div>
									<div class="card-body">
										<form method="POST" id="update-municipal-head" enctype="multipart/form-data">
											@csrf
											<div class="row">
												<div class="col-md-4 form-group {{ ($errors->has('municipal_id')) ? 'has-error' : '' }}">
													<label class="form-control-label">Municipal</label>
													<select name="municipal_id" id="municipal_id" class="form-control">
														<option value="">--Select Municipal--</option>
														@foreach ($municipals as $municipal)
															<option value="{{ $municipal->id }}" {{ ($edit->municipal_id) == $municipal->id ? 'selected' : '' }}>{{ $municipal->name }}</option>
														@endforeach
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
												<div class="col-md-4 form-group {{ ($errors->has('gender')) ? 'has-error' : '' }}">
													<label class="form-control-label">Gender</label>
													<select name="gender" id="gender" class="form-control">
														<option value="">--Select Gender--</option>
														<option value="Male" {{ ($edit->gender) == 'Male' ? 'selected' : '' }}>Male</option>
														<option value="Female" {{ ($edit->gender) == 'Female' ? 'selected' : '' }}>Female</option>
													</select>
													@if ($errors->has('gender'))
														<strong class="help-block">{{ $errors->first('gender') }}</strong>
													@endif
												</div>
											</div>
											<div class="row">
												<div class="col-md-4 form-group {{ ($errors->has('dob')) ? 'has-error' : '' }}">
													<label class="form-control-label">Date Of Birth</label>
													<input type="text" name="dob" id="dob" placeholder="Date Of Birth" class="form-control datepicker" value="{{ $edit->dob }}">
													@if ($errors->has('dob'))
														<strong class="help-block">{{ $errors->first('dob') }}</strong>
													@endif
												</div>
												<div class="col-md-4 form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
													<label class="form-control-label">Phone</label>
													<input type="number" name="phone" id="phone" placeholder="XXXXXXXXXX" class="form-control" min="0" value="{{ $edit->phone }}">
													@if ($errors->has('phone'))
														<strong class="help-block">{{ $errors->first('phone') }}</strong>
													@endif
												</div>
												<div class="col-md-4 form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
													<label class="form-control-label">Email</label>
													<input type="email" name="email" id="email" placeholder="name@domain.com" class="form-control" value="{{ $edit->email }}">
													@if ($errors->has('email'))
														<strong class="help-block">{{ $errors->first('email') }}</strong>
													@endif
												</div>
											</div>
											<div class="row">
												<div class="col-md-8 form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
													<label class="form-control-label">Address</label>
													<input type="text" name="address" id="address" placeholder="Address" class="form-control" value="{{ $edit->address }}">
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
	@include('district_layouts.scripts')
	<script>

		// jquery email method
		jQuery.validator.addMethod("customemail", function(value, element) {
			return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
		}, "Please enter a valid email address.");

		// create form validator
		$('#municipal-head-form').validate({
			rules: {
				municipal_id: { required: true },
				fullname: { required: true },
				gender: { required: true },
				dob: { required: true },
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
				gender: "Select gender.",
				dob: "Enter date of birth.",
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
		$('#update-municipal-head').validate({
			rules: {
				municipal_id: { required: true },
				fullname: { required: true },
				gender: { required: true },
				dob: { required: true },
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
				gender: "Select gender.",
				dob: "Enter date of birth.",
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
		$('body').on('submit', '#municipal-head-form', function(){
			$('#create-btn').prop('disabled', true);
			$('#create-btn').html('Creating...');
		});

		$('body').on('submit', '#update-municipal-head', function(){
			$('#update-btn').prop('disabled', true);
			$('#update-btn').html('Updating..');
		});
	</script>
</body>
</html>
