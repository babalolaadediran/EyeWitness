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
                        <div class="col-md-8 mb-5">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="h6 text-uppercase mb-0">Profile</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ url('municipal/update/profile') }}" id="update-profile" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-7 form-group {{ ($errors->has('fullname')) ? 'has-error' : '' }}">
                                                <label class="form-control-label">Fullname</label>
                                                <input type="text" name="fullname" id="fullname" placeholder="Fullname" class="form-control" value="{{ $municipal_head->fullname }}">
                                                @if ($errors->has('fullname'))
                                                    <strong class="help-block">{{ $errors->first('fullname') }}</strong>
                                                @endif
                                            </div>
                                            <div class="col-md-5 form-group {{ ($errors->has('gender')) ? 'has-error' : '' }}">
                                                <label class="form-control-label">Gender</label>
                                                <select name="gender" id="gender" class="form-control">
                                                    <option value="">--Select Gender--</option>
                                                    <option value="Male" {{ ($municipal_head->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ ($municipal_head->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                                </select>
                                                @if ($errors->has('gender'))
                                                    <strong class="help-block">{{ $errors->first('gender') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5 form-group {{ ($errors->has('dob')) ? 'has-error' : '' }}">
                                                <label class="form-control-label">Date Of Birth</label>
                                                <input type="text" name="dob" id="dob" placeholder="Date Of Birth" class="form-control datepicker" value="{{ $municipal_head->dob }}">
                                                @if ($errors->has('dob'))
                                                    <strong class="help-block">{{ $errors->first('dob') }}</strong>
                                                @endif
                                            </div>
                                            <div class="col-md-7 form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
                                                <label class="form-control-label">Phone</label>
                                                <input type="number" name="phone" id="phone" placeholder="XXXXXXXXXX" class="form-control" min="0" value="{{ $municipal_head->phone }}">
                                                @if ($errors->has('phone'))
                                                    <strong class="help-block">{{ $errors->first('phone') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                                                <label class="form-control-label">Email</label>
                                                <input type="email" name="email" id="email" placeholder="name@domain.com" class="form-control" value="{{ $municipal_head->email }}">
                                                @if ($errors->has('email'))
                                                    <strong class="help-block">{{ $errors->first('email') }}</strong>
                                                @endif
                                            </div>
                                            <div class="col-md-6 form-group {{ ($errors->has('picture')) ? 'has-error' : '' }}">
                                                <label class="form-control-label">Picture</label>
                                                <input type="file" name="picture" id="picture" placeholder="Picture" class="form-control" accept="image/*">
                                                @if ($errors->has('picture'))
                                                    <strong class="help-block">{{ $errors->first('picture') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
                                                <label class="form-control-label">Address</label>
                                                <input type="text" name="address" id="address" placeholder="Address" class="form-control" value="{{ $municipal_head->address }}">
                                                @if ($errors->has('address'))
                                                    <strong class="help-block">{{ $errors->first('address') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <button id="update-profile-btn" class="btn btn-primary">Update Profile</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="h6 text-uppercase mb-0">Change Password</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ url('municipal/update/password') }}" id="update-password">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12 form-group {{ ($errors->has('old_password')) ? 'has-error' : '' }}">
                                                <label class="form-control-label">Old Password</label>
                                                <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                                                @if ($errors->has('old_password'))
                                                    <strong class="help-block">{{ $errors->first('old_password') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group {{ ($errors->has('new_password')) ? 'has-error' : '' }}">
                                                <label class="form-control-label">New Password</label>
                                                <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                                                @if ($errors->has('new_password'))
                                                    <strong class="help-block">{{ $errors->first('new_password') }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label class="form-control-label">Retype Password</label>
                                                <input type="password" name="retype_password" id="retype_password" placeholder="Retype Password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <button id="update-password-btn" class="btn btn-primary">Update Password</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
		$('#update-profile').validate({
			rules: {
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

        // update password
        $('#update-password').validate({
            rules: {
                old_password: {required:true},
                new_password: {
                    required:true,
                    minlength: 6
                },
                retype_password: {
                    minlength: 6,
                    equalTo: "#new_password"
                }
            },
            messages: {
                old_password: "Enter your old password.",
                new_password: {
                    required: "Enter your new password.",
                    minlength: "Minimum of {0} characters."
                },
                retype_password: "Password does not match."
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
            },
        });

        // alert handler
		window.setTimeout(function () {
			$("#alert-msg").fadeOut(400).remove(600);
		}, 4000);

        // btn handler
		$('body').on('submit', '#update-profile', function(){
			$('#update-profile-btn').prop('disabled', true);
			$('#update-profile-btn').html('Updating Profile...');
		});

		$('body').on('submit', '#update-password', function(){
            $('#update-password-btn').prop('disabled', true);
            $('#update-password-btn').html('Updating Password...');
        });
    </script>
</body>
</html>
