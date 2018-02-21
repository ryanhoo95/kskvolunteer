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
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/dist/css/AdminLTE.min.css")}}">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
            page. However, you can choose any other skin. Make sure you
            apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/dist/css/skins/skin-red.min.css")}}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>
<body class="hold-transition skin-red sidebar-mini">
    <div id="app">
        <div class="wrapper">

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
                
        </div>
        <!-- ./wrapper -->
                
        <!-- REQUIRED JS SCRIPTS -->
        
        <!-- jQuery 3 -->
        <script src="{{asset("css/admin-lte/bower_components/jquery/dist/jquery.min.js")}}"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{asset("css/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js")}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset("css/admin-lte/dist/js/adminlte.min.js")}}"></script>
    </div>
</body>
</html>
