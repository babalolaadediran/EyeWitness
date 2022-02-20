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
	<style>
        #map {
            width: 100%;
            height: 300px;
            background-color: grey;
        }
    </style>
</head>
<body>
	@include('district_layouts.header')
	<div class="d-flex align-items-stretch">
		@include('district_layouts.sidebar')
		<div class="page-holder w-100 d-flex flex-wrap">
			<div class="container-fluid px-xl-5">
				<section class="py-5">
					<div class="row">
                        <div class="col-md-4 mb-4">
							<div class="card">
                                <div id="map"></div>
								<div class="card-body">
									<p>
										<span style="line-height: 30px;">{{ $report_details->incident }}</span><br>
                                        <span style="line-height: 30px;">
                                            <i class="fa fa-calendar"></i> 
                                            {{ \Carbon\Carbon::parse($report_details->created_at)->format('d/M/Y') }}
                                        </span><br>
                                        <span style="line-height: 30px;">
                                            <i class="fa {{ ($report_details->status) != 'Approved' ? 'fa-times' : 'fa-check' }}"></i> 
                                            {{ $report_details->status }}
                                        </span>
                                    </p>
                                    <p>
                                        <input type="hidden" name="report" id="report" value="{{ $report_details->id }}">
                                        @if ($report_details->status != 'Approved')                                            
                                            <button id="push" class="btn btn-sm btn-primary">Push to Agency</button>    
                                        @endif
                                    </p>									
								</div>								
                            </div>
                            <br>
                            <div class="card">
                                <div class="card-header">
                                    <h6><strong>Reported By</strong></h6>
                                </div>
                                <div class="card-body">
                                    <img src="data:image/png;base64,{{ $report_details->picture }}" class="avatar" alt="Reporter Image">
                                    <br><br>                                    
                                    <h6>{{ $report_details->fullname }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Incident Media</strong>
                                </div>
                                <div class="card-body">
                                    @if ($report_details->media_type == 'IMAGE')
									    <img src="data:image/png;base64,{{ $report_details->media_url }}" alt="Report Image" style="height: 100%; width: 100%;">
                                    @elseif($report_details->media_type == 'VIDEO')
                                        <video src="data:video/mp4;base64,{{ $report_details->media_url }}" controls style="height: 100%; width: 100%;"></video>
                                    @endif                                    
                                </div>
                            </div>
                        </div>						
					</div>
				</section>
			</div>
		</div>
	</div>
	@include('district_layouts.scripts')
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAEgLF1oFwVEkE8ObziBrvxIsf8lSEnl6g&callback=initMap"></script>
    <script>
		$.ajaxSetup({
			headers:{
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

        function initMap() {
            let location = {lat: {{$report_details->latitude}}, lng: {{$report_details->longitude}} };
            let map = new google.maps.Map(document.getElementById('map'), {zoom: 12, center: location});
            let marker = new google.maps.Marker({
                position: location,
                title: 'Location',
                map:map
            });
        }

        // push to agency
        $('body').on('click', '#push', function(){

            // report
            let report = $('#report').val();
            
            // button handler
            $('#push').attr('disabled', true);
            $('#push').html('<i class="fa fa-spinner fa-spin"></i>');

            // send request
            $.ajax({
                type: "POST",
                url: "{{ url('/district/approve/report') }}",
                data: {report:report},
                success: (response) => {
                    if(response.status == 200){
                        swal({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            buttons: false
                        });
                        window.setTimeout(() => {
                            window.location.reload();
                        }, 4000);
                    }else{
                        swal({
                            title: "Error",
                            text: response.message,
                            icon: "warning",
                            dangerMode: true,
                            buttons: false
                        });                        
                        $('#push').attr('disabled', false);
                        $('#push').html('Push to Agency');
                    }
                }
            });
        });
    </script>
</body>
</html>
