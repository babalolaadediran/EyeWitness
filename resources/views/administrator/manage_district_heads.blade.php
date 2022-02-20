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
						<div class="col-lg-12 mb-5">
							<div class="card">
								<div class="card-header">
									<h6 class="text-uppercase mb-0">
										Manage District Heads
										<a href="{{ url('administrator/district/head') }}" class="float-right btn btn-sm btn-rounded btn-info">New District Head</a>
									</h6>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="zero_config" class="table table-condensed display responsive nowrap card-text" style="width: 100%;" cellspacing="0">
											<thead>
												<tr>
												<th>#</th>
												<th>Picture</th>
												<th>Fullname</th>
												<th>Gender</th>
												<th>Date Of Birth</th>
												<th>Phone</th>
												<th>Email</th>
												<th>Address</th>
												<th>District</th>
												<th>Action</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($district_heads as $district_head)
													<tr>
														<th scope="row">{{ $loop->iteration }}.</th>
														<th>
															<img src="data:image/png;base64,{{ $district_head->picture }}" alt="District Head Image" class="avatar">
														</th>
														<td>{{ $district_head->fullname }}</td>
														<td>{{ $district_head->gender }}</td>
														<td>{{ \Carbon\Carbon::parse($district_head->dob)->format('d-M-Y') }}</td>
														<td>{{ $district_head->phone }}</td>
														<td>{{ $district_head->email }}</td>
														<td>{{ $district_head->address }}</td>
														<td>{{ $district_head->district }}</td>
														<td>
															<a href="{{ url('administrator/edit/district/head/'.$district_head->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
															<a href="javascript:void(0);" id="{{ $district_head->id }}" class="delete btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
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

		// delete district head handler
        $('body').on('click', '.delete', function(){
            let id = this.id;
            swal({
                title: "Are yout sure to delete ?",
                text: "This action is irreversible and the district head's data will be lost.",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then((willDelete) => {
                if(willDelete){

                    // send request
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('administrator/delete/district/head') }}",
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
	</script>
</body>
</html>
