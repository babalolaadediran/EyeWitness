<!-- JavaScript files-->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/popper.js/umd/popper.min.js') }}"> </script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/jquery.cookie/jquery.cookie.js') }}"> </script>
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="{{ asset('js/charts-custom.js') }}"></script>
<script src="{{ asset('js/charts-home.js') }}"></script>
<script src="{{ asset('js/front.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/sweet-alert.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('datatables/datatables.min.js') }}"></script>
<script src="{{ asset('datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/responsive.dataTables.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // logout handler
    $('body').on('click', '#logout-link', function(){
    	$('#logout-form').submit();
    });

    // initiate datepicker
    $('.datepicker').datepicker();

    // initiate datable
    $('#zero_config').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ]
    });

    window.addEventListener('load', e => {
       var menu = $('.sidebar-list-item');
       
        for(var i = 0; i < menu.length; i++){

            var anchorMenu = menu[i].children[0].pathname;

            if(anchorMenu == window.location.pathname){                
                menu[i].children[0].setAttribute('class', 'sidebar-link text-muted active');
            }else{
                menu[i].children[0].setAttribute('class', 'sidebar-link text-muted');                
            }
        }
   });
</script>