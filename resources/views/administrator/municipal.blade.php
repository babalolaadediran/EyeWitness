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
										<h3 class="h6 text-uppercase mb-0">Create Municipal</h3>
									</div>
									<div class="card-body">
										<form method="POST" id="municipal-form" enctype="multipart/form-data">
											@csrf
											<div class="form-group {{ ($errors->has('district_id')) ? 'has-error' : '' }}">
												<label class="form-control-label">District</label>
												<select name="district_id" id="district_id" class="form-control">
													<option value="">--Select District--</option>
													@foreach ($districts as $district)
														<option value="{{ $district->id }}" {{ (old('district_id')) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
													@endforeach
												</select>
											</div>
											<div class="form-group {{ ($errors->has('municipal_name')) ? 'has-error' : '' }}">
												<label class="form-control-label">Municipal Name</label>
												<input type="text" name="municipal_name" id="municipal_name" placeholder="eg. Greater Giyani" class="form-control" value="{{ old('municipal_name') }}">
											</div>
											<div class="form-group {{ ($errors->has('longitude')) ? 'has-error' : '' }}">
												<label class="form-control-label">Longitude</label>
												<input type="number" name="longitude" id="longitude" placeholder="eg. 3.75685" class="form-control" value="{{ old('longitude') }}">
											</div>
											<div class="form-group {{ ($errors->has('latitude')) ? 'has-error' : '' }}">
												<label class="form-control-label">Latitude</label>
												<input type="number" name="latitude" id="latitude" placeholder="eg. 8.67546" class="form-control" value="{{ old('latitude') }}">
											</div>
											<div class="form-group {{ ($errors->has('logo')) ? 'has-error': '' }}">
												<label class="form-control-label">Logo</label>
												<input type="file" name="logo" id="logo" placeholder="Logo" accept="image/*">
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
										<h3 class="h6 text-uppercase mb-0">Update Municipal</h3>
									</div>
									<div class="card-body">
										<form method="POST" id="edit-municipal-form" enctype="multipart/form-data">
											@csrf
											<div class="form-group {{ ($errors->has('district_id')) ? 'has-error' : '' }}">
												<label class="form-control-label">District</label>
												<select name="district_id" id="district_id" class="form-control">
													<option value="">--Select District--</option>
													@foreach ($districts as $district)
														<option value="{{ $district->id }}" {{ ($edit->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
													@endforeach
												</select>
											</div>
											<div class="form-group {{ ($errors->has('municipal_name')) ? 'has-error' : '' }}">
												<label class="form-control-label">Name</label>
												<input type="text" name="municipal_name" id="municipal_name" placeholder="eg. Greater Giyani" class="form-control" value="{{ $edit->name }}">
											</div>
											<div class="form-group {{ ($errors->has('longitude')) ? 'has-error' : '' }}">
												<label class="form-control-label">Longitude</label>
												<input type="number" name="longitude" id="longitude" placeholder="eg. 3.75685" class="form-control" value="{{ $edit->longitude }}">
											</div>
											<div class="form-group {{ ($errors->has('latitude')) ? 'has-error' : '' }}">
												<label class="form-control-label">Latitude</label>
												<input type="number" name="latitude" id="latitude" placeholder="eg. 8.67546" class="form-control" value="{{ $edit->latitude }}">
											</div>
											<div class="form-group {{ ($errors->has('logo')) ? 'has-error': '' }}">
												<label class="form-control-label">Logo</label>
												<input type="file" name="logo" id="logo" placeholder="Logo" accept="image/*">
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
									<h6 class="text-uppercase mb-0">Manage Municipals</h6>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="zero_config" class="table table-condensed display responsive nowrap card-text" style="width: 100%;" cellspacing="0">
											<thead>
												<tr>
												<th>#</th>
												<th>Municipal Name</th>
												<th>District</th>
												<th>Longitude</th>
												<th>Latitude</th>
												<th>Logo</th>
												<th>Action</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($municipals as $municipal)
													<tr>
														<th scope="row">{{ $loop->iteration }}.</th>
														<td>{{ $municipal->name }}</td>
														<td>{{ $municipal->district }}</td>
														<td>{{ $municipal->longitude }}</td>
														<td>{{ $municipal->latitude }}</td>
														<td><img src="data:image/png;base64,{{ $municipal->logo }}" class="avatar" alt="Logo"></td>
														<td>
															<a href="{{ url('administrator/edit/municipal/'.$municipal->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
															<a href="javascript:void(0);" id="{{ $municipal->id }}" class="delete btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
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

		// create form validator
		$('#municipal-form').validate({
			rules: {
				district_id: { required: true },
				municipal_name: { required: true },
				longitude: { required: true },
				latitude: { required: true },
				logo: { required: true }
			},
			messages: {
				district_id: "Select district.",
				municipal_name: "Enter municipal name.",
				longitude: "Enter longitude.",
				latitude: "Enter latitude.",
				logo: "Select logo"
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

		// edit form validator
		$('#edit-municipal-form').validate({
			rules: {
				district_id: { required: true },
				municipal_name: { required: true },
				longitude: { required: true },
				latitude: { required: true },				
			},
			messages: {
				district_id: "Select district.",
				municipal_name: "Enter municipal name.",
				longitude: "Enter longitude.",
				latitude: "Enter latitude.",				
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

		// delete district handler
		$('body').on('click', '.delete', function(){
			let id = this.id;
			swal({
				title: "Are yout sure to delete ?",
				text: "This action is irreversible and the municipal's data will be lost.",
				icon: "warning",
				buttons: true,
				dangerMode: true
			}).then((willDelete) => {
				if(willDelete){

					// send request
					$.ajax({
						type: "DELETE",
						url: "{{ url('administrator/delete/municipal') }}",
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
		$('body').on('submit', '#municipal-form', function(){
			$('#create-btn').prop('disabled', true);
			$('#create-btn').html('Creating...');
			$('#update-btn').prop('disabled', true);
			$('#update-btn').html('Updating..');
		});
	</script>
</body>
</html>
