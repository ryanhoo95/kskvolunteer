<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/bower_components/bootstrap/dist/css/bootstrap.min.css")}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/bower_components/font-awesome/css/font-awesome.min.css")}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/bower_components/Ionicons/css/ionicons.min.css")}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css")}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/bower_components/select2/dist/css/select2.min.css")}}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/bower_components/bootstrap-daterangepicker/daterangepicker.css")}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css")}}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/plugins/timepicker/bootstrap-timepicker.min.css")}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/dist/css/AdminLTE.min.css")}}">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
            page. However, you can choose any other skin. Make sure you
            apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/dist/css/skins/skin-red.min.css")}}">
    <!-- iCheck for checkboxes and radio inputs -->
     <link rel="stylesheet" href="{{asset("css/admin-lte/plugins/iCheck/all.css")}}">
     <!-- password strenth meter -->
     <link rel="stylesheet" href="{{asset("css/admin-lte/plugins/password-strength-meter/password.min.css")}}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>
<body class="hold-transition skin-red fixed sidebar-mini">
    <div id="app">
        <div class="wrapper">
           @if (Auth::guest())
                <script type="text/javascript">
                    window.location = "{{ route('login') }}";
                </script>
            @else
                <!-- Header -->
                @include('layouts/header')
                
                <!-- Sidebar -->
                @include('layouts/sidebar')
                
                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <!-- /.content-wrapper -->
                
                <!-- footer -->
                @include('layouts/footer')
            @endif
            
            
                
        </div>
        <!-- ./wrapper -->
                
        <!-- REQUIRED JS SCRIPTS -->
        
        <!-- jQuery 3 -->
        <script src="{{asset("css/admin-lte/bower_components/jquery/dist/jquery.min.js")}}"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{asset("css/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js")}}"></script>
        <!-- DataTables -->
        <script src="{{asset("css/admin-lte/bower_components/datatables.net/js/jquery.dataTables.min.js")}}"></script>
        <script src="{{asset("css/admin-lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js")}}"></script>
        <!-- SlimScroll -->
        <script src="{{asset("css/admin-lte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js")}}"></script>
        <!-- FastClick -->
        <script src="{{asset("css/admin-lte/bower_components/fastclick/lib/fastclick.js")}}"></script>   
        <!-- AdminLTE App -->
        <script src="{{asset("css/admin-lte/dist/js/adminlte.min.js")}}"></script>
        <!-- Select2 -->
        <script src="{{asset("css/admin-lte/bower_components/select2/dist/js/select2.full.min.js")}}"></script>
        <!-- bootstrap time picker -->
        <script src="{{asset("css/admin-lte/plugins/timepicker/bootstrap-timepicker.min.js")}}"></script>
        <!-- date-range-picker -->
        <script src="{{asset("css/admin-lte/bower_components/moment/min/moment.min.js")}}"></script>
        <script src="{{asset("css/admin-lte/bower_components/bootstrap-daterangepicker/daterangepicker.js")}}"></script>
        <!-- bootstrap datepicker -->
        <script src="{{asset("css/admin-lte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")}}"></script>
        <!-- iCheck 1.0.1 -->
        <script src="{{asset("css/admin-lte/plugins/iCheck/icheck.min.js")}}"></script>
        <!-- password strength meter -->
        <script src="{{asset("css/admin-lte/plugins/password-strength-meter/password.min.js")}}"></script>

        <script type="text/javascript">
            $(function () {
                var today = new Date();
                var tomorrow = new Date();
                tomorrow.setDate(today.getDate()+1);

                //data table
                $('#datatable').DataTable({
                    "order": []
                })

                //date picker
                $('#datepicker').datepicker({
                    autoclose: true,
                    format: 'dd M yyyy'
                })

                 //date picker min
                 $('#datepicker_min').datepicker({
                    startDate: today,
                    autoclose: true,
                    format: 'dd M yyyy',
                })

                //Timepicker
                $('.timepicker').timepicker({
                    showInputs: false
                })

                //Date range picker
                $('#date_range').daterangepicker({
                    minDate: tomorrow,
                    startDate: tomorrow,
                    endDate: tomorrow,
                    locale: {
                        format: 'DD MMM YYYY'
                    }
                })

                //password strength meter
                $('#password').password({
                    shortPass: 'The password is too short',
                    badPass: 'Weak; try combining letters & numbers',
                    goodPass: 'Medium; try using special charecters',
                    strongPass: 'Strong password',
                    containsUsername: 'The password contains the username',
                    enterPass: null,
                    showPercent: false,
                    showText: true, // shows the text tips 
                    animate: true, // whether or not to animate the progress bar on input blur/focus 
                    animateSpeed: 'fast', // the above animation speed 
                    username: false, // select the username field (selector or jQuery instance) for better password checks 
                    usernamePartialMatch: true, // whether to check for username partials 
                    minimumLength: 8 // minimum password length (below this threshold, the score is 0) 
                });
            })
        </script>
    </div>
</body>
</html>
