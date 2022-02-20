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
						@if (count($reports))
							@foreach ($reports as $report)
								<div class="col-md-3 mb-4">
									<div class="card">										
										@if ($report->media_type == 'IMAGE')
											<img src="data:image/png;base64,{{ $report->media_url }}" alt="Report Image" style="height: 150px; width: 100%; border-radius: 10px 10px 0px 0px;">
										@elseif($report->media_type == 'VIDEO')
											<video src="data:video/mp4;base64,{{ $report->media_url }}" controls style="height: 120px; width: 100%;"></video>
										@endif						
										<div class="card-body">
											<p>
												<span style="line-height: 30px;">{{ Str::limit($report->incident, 70) }}</span>
												<br>
												<span style="line-height: 30px;">
													<i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y') }}
												</span>												
											</p>
											<a href="{{ url('district/report/details/'.$report->id) }}" class="btn btn-primary btn-sm">View</a>
										</div>								
									</div>
								</div>						
							@endforeach
						@else
							<div class="col-md-4 offset-md-3">
								<h4 class="text-danger" style="text-align: center;">
									<strong>No incident reported yet.</strong>
								</h4>
							</div>							
						@endif
					</div>
					<div class="row">
						<div class="col-md-6 offset-md-5">
							{{ $reports->links() }}
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	@include('district_layouts.scripts')
</body>
</html>
