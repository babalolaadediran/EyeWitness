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
						<div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
							<div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
								<div class="flex-grow-1 d-flex align-items-center">
									<div class="dot mr-3 bg-violet"></div>
									<div class="text">
										<h6 class="mb-0">Total District</h6><span class="text-gray">{{ number_format($total_district) }}</span>
									</div>
								</div>
								<div class="icon text-white bg-violet"><i class="fas fa-map-marker"></i></div>
							</div>
						</div>
						<div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
							<div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
								<div class="flex-grow-1 d-flex align-items-center">
									<div class="dot mr-3 bg-green"></div>
									<div class="text">
										<h6 class="mb-0">Total Municipal</h6><span class="text-gray">{{ number_format($total_municipal) }}</span>
									</div>
								</div>
								<div class="icon text-white bg-green"><i class="fas fa-map-marker"></i></div>
							</div>
						</div>
						<div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
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
						<div class="col-xl-3 col-lg-6 mb-4 mb-xl-0">
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
				<section class="py-5">
					<div class="row mb-4">
						<div class="col-lg-12">
							<div class="card">
								<div class="card-header">
									<h2 class="h6 mb-0 text-uppercase">Citizens Distribution by Municipal</h2>
								</div>
								<div class="card-body">
									<p class="mb-5 text-gray"></p>
									<div class="chart-holder mt-5 mb-5">
										<canvas id="citizenChart"></canvas>
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
		var violet = '#DF99CA',
        red    = '#F0404C',
		green  = '#7CF29C';
		
		var ctx1 = $("canvas").get(0).getContext("2d");
		var gradient1 = ctx1.createLinearGradient(150, 0, 150, 300);
		gradient1.addColorStop(0, 'rgba(210, 114, 181, 0.91)');
		gradient1.addColorStop(1, 'rgba(177, 62, 162, 0)');

		var gradient2 = ctx1.createLinearGradient(10, 0, 150, 300);
		gradient2.addColorStop(0, 'rgba(252, 117, 176, 0.84)');
		gradient2.addColorStop(1, 'rgba(250, 199, 106, 0.92)');

		/*
		* Bar chart for municipal citizens distribution
		*/
		var BARCHARTEXMPLE    = $('#citizenChart');
		var barChartExample = new Chart(BARCHARTEXMPLE, {
			type: 'bar',
			options: {
				scales: {
					xAxes: [{
						display: true,
						gridLines: {
							color: '#fff'
						}
					}],
					yAxes: [{
						display: true,
						gridLines: {
							color: '#fff'
						}
					}]
				},
				legend: false
			},
			data: {
				labels: [
					@php 
						foreach($municipals as $municipal){
							echo "'$municipal->name'".',';
						}					
					@endphp
				],
				datasets: [
					{
						label: "Citizens",
						backgroundColor: [
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2
						],
						hoverBackgroundColor: [
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2
						],
						borderColor: [
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2,
							gradient2
						],
						borderWidth: 1,
						data: [

							@php 
								foreach($municipals as $municipal){
									
									$citizen_count = App\User::where('municipal_id', $municipal->id)->count();

									echo $citizen_count.',';
								}
							@endphp
						],
					}
				]
			}
		});
	</script>
</body>
</html>
