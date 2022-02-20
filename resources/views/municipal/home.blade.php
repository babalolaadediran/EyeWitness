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
						<div class="col-xl-4 col-lg-6 mb-4 mb-xl-0">
							<div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
								<div class="flex-grow-1 d-flex align-items-center">
									<div class="dot mr-3 bg-blue"></div>
									<div class="text">
										<h6 class="mb-0">Total Citizens</h6><span class="text-gray">{{ number_format($citizens) }}</span>
									</div>
								</div>
								<div class="icon text-white bg-blue"><i class="fa fa-users"></i></div>
							</div>
						</div>
						<div class="col-xl-4 col-lg-6 mb-4 mb-xl-0">
							<div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
								<div class="flex-grow-1 d-flex align-items-center">
									<div class="dot mr-3 bg-red"></div>
									<div class="text">
										<h6 class="mb-0">Total Reports</h6><span class="text-gray">{{ number_format($total_reports) }}</span>
									</div>
								</div>
								<div class="icon text-white bg-red"><i class="fas fa-receipt"></i></div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	@include('municipal_layouts.scripts')
</body>
</html>
