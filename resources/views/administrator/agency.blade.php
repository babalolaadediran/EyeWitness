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
	@include('admin_layouts.styles')
</head>
<body>
	@include('admin_layouts.header')
	<div class="d-flex align-items-stretch">
		@include('admin_layouts.sidebar')
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
						<div class="col-lg-4 mb-5">
							@if (empty($edit))
								<div class="card">
									<div class="card-header">
										<h3 class="h6 text-uppercase mb-0">Create Agency</h3>
									</div>
									<div class="card-body">
										<form method="POST" id="agency-form">
											@csrf
											<div class="form-group">
												<label class="form-control-label">Municipal</label>
												<select name="municipal" id="municipal" class="form-control">
													<option value="">-- Select Municipal --</option>
													@foreach ($municipals as $municipal)														
														<option value="{{ $municipal->id }}" {{ (old('municipal')) == $municipal->id ? 'selected' : '' }}>{{ $municipal->name }}</option>
													@endforeach
												</select>
											</div>
											<div class="form-group {{ ($errors->has('agency_name')) ? 'has-error' : '' }}">
												<label class="form-control-label">Agency Name</label>
												<input type="text" name="agency_name" id="agency_name" placeholder="eg. Police Force" class="form-control" value="{{ old('agency_name') }}">
											</div>
											<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
												<label class="form-control-label">Description</label>
												<input type="text" name="description" id="description" placeholder="Description" class="form-control" value="{{ old('description') }}">
											</div>
											<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
												<label class="form-control-label">Email</label>
												<input type="email" name="email" id="email" placeholder="name@domain.com" class="form-control" value="{{ old('email') }}">
                                            </div>
                                            <div class="form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
												<label class="form-control-label">Phone</label>
												<input type="number" name="phone" id="phone" placeholder="0897645321" class="form-control" value="{{ old('phone') }}" min="0">
                                            </div>
                                            <div class="form-group {{ ($errors->has('location')) ? 'has-error' : '' }}">
												<label class="form-control-label">Location</label>
												<input type="text" name="location" id="location" placeholder="eg. Giyani" class="form-control" value="{{ old('location') }}">
											</div>
											<div class="form-group">
												<button id="create-btn" type="submit" class="btn btn-primary">Create</button>
											</div>
										</form>
									</div>
								</div>
							@else
								<div class="card">
									<div class="card-header">
										<h3 class="h6 text-uppercase mb-0">Update Agency</h3>
									</div>
									<div class="card-body">
										<form method="POST" id="agency-form">
											@csrf
											<div class="form-group">
												<label class="form-control-label">Municipal</label>
												<select name="municipal" id="municipal" class="form-control">
													<option value="">-- Select Municipal --</option>
													@foreach ($municipals as $municipal)														
														<option value="{{ $municipal->id }}" {{ ($edit->municipal_id) == $municipal->id ? 'selected' : '' }}>{{ $municipal->name }}</option>
													@endforeach
												</select>
											</div>
                                            <div class="form-group {{ ($errors->has('agency_name')) ? 'has-error' : '' }}">
												<label class="form-control-label">District Name</label>
												<input type="text" name="agency_name" id="agency_name" placeholder="eg. Police Force" class="form-control" value="{{ $edit->agency_name }}">
											</div>
											<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
												<label class="form-control-label">Description</label>
												<input type="text" name="description" id="description" placeholder="Description" class="form-control" value="{{ $edit->description }}">
											</div>
											<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
												<label class="form-control-label">email</label>
												<input type="email" name="email" id="email" placeholder="name@domain.com" class="form-control" value="{{ $edit->email }}">
                                            </div>
                                            <div class="form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
												<label class="form-control-label">Phone</label>
												<input type="number" name="phone" id="phone" placeholder="0897645321" class="form-control" value="{{ $edit->phone }}" min="0">
                                            </div>
                                            <div class="form-group {{ ($errors->has('location')) ? 'has-error' : '' }}">
												<label class="form-control-label">Location</label>
												<input type="text" name="location" id="location" placeholder="eg. Giyani" class="form-control" value="{{ $edit->location }}">
											</div>
											<div class="form-group">
												<button id="update-btn" type="submit" class="btn btn-primary">Update</button>
											</div>
										</form>
									</div>
								</div>
							@endif
						</div>
						<div class="col-lg-8 mb-5">
							<div class="card">
								<div class="card-header">
								  	<h6 class="text-uppercase mb-0">Manage Agencies</h6>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="zero_config" class="table table-condensed display responsive nowrap card-text" style="width: 100%;" cellspacing="0">
										  	<thead>
											  	<tr>
													<th>#</th>
													<th>Municipal</th>
													<th>Agency Name</th>
													<th>Description</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Location</th>
													<th>Action</th>
											  	</tr>
										  	</thead>
										  	<tbody>
												@foreach ($agencies as $agency)
													<tr>
														<th scope="row">{{ $loop->iteration }}.</th>
														<td>{{ $agency->name }}</td>
														<td>{{ $agency->agency_name }}</td>
														<td>{{ $agency->description }}</td>
                                                        <td>{{ $agency->email }}</td>
                                                        <td>{{ $agency->phone }}</td>
                                                        <td>{{ $agency->location }}</td>
														<td>
															<a href="{{ url('administrator/edit/agency/'.$agency->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
															<a href="javascript:void(0);" id="{{ $agency->id }}" class="delete btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
															<a href="tel:{{ $agency->phone }}" class="btn btn-sm btn-info" title="Call"><i class="fa fa-phone"></i></a>
														</td>
													</tr>
												@endforeach
										  	</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	@include('admin_layouts.scripts')
	<script>
		$.ajaxSetup({
			headers:{
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		// alert handler
		window.setTimeout(function () {
			$("#alert-msg").fadeOut(400).remove(600);
		}, 4000);

        // jquery email method
		jQuery.validator.addMethod("customemail", function(value, element) {
			return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
		}, "Please enter a valid email address.");

		// form validator
		$('#agency-form').validate({
			rules: {
				municipal: { required: true },
				agency_name: { required: true },
				description: { required: true },
				email: {
                    required: true,
                    customemail: true
                },
                phone: { required: true },
                location: { required:true }
			},
			messages: {
				municipal: "Select municipal",
				agency_name: "Enter agency name.",
				description: "Enter agency description.",
				email: { required: "Enter agency email." },
                phone: "Enter agency phone.",
                location: "Enter agency location."
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

        // delete agency handler
        $('body').on('click', '.delete', function(){
            let id = this.id;
            swal({
                title: "Are yout sure to delete ?",
                text: "This action is irreversible and the agency's data will be lost.",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then((willDelete) => {
                if(willDelete){

                    // send request
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('administrator/delete/agency') }}",
                        data:{id:id},
                        success:function(response) {
                            if(response.status == 200){
                                swal({
                                    title: "Success",
                                    text: response.message,
                                    icon: "success",
                                    buttons: false
                                });
                                window.setTimeout(function(){
                                    window.location.reload();
                                }, 4000);
                            }else{
                                swal({
                                    title: "Success",
                                    text: response.message,
                                    icon: "success",
                                    buttons: false
                                });
                                window.setTimeout(function(){
                                    window.location.reload();
                                }, 4000);
                            }
                        }
                    })
                }
            });
        });

		// btn handler
		$('body').on('submit', '#agency-form', function(){
			$('#create-btn').prop('disabled', true);
			$('#create-btn').html('Creating...');
			$('#update-btn').prop('disabled', true);
			$('#update-btn').html('Updating..');
		});
	</script>
</body>
</html>
