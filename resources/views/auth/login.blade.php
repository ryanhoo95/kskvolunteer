<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
        <!-- iCheck -->
    <link rel="stylesheet" href="{{asset("css/admin-lte/plugins/iCheck/square/blue.css")}}">

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition login-page" style="overflow:hidden">
        <div class="login-box">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-exclamation"></i> Fail</h4>
                    {{ session('error') }}
                </div>
            @endif

            <div class="login-logo">
                <img src="/storage/logo.png" alt="logo" width="250px">
                <p><b>KSK Volunteer</b></p>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Welcome</p>

                <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                    <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                            <input type="checkbox" {{ old('remember') ? 'checked' : '' }}> Remember Me
                            </label>
                        </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
                        </div>
                        <!-- /.col -->
                    </div>
                
                </form>
            
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        
        <!-- jQuery 3 -->
        <script src="{{asset("css/admin-lte/bower_components/jquery/dist/jquery.min.js")}}"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{asset("css/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js")}}"></script>
        <!-- iCheck -->
        <script src="{{asset("css/admin-lte/plugins/iCheck/icheck.min.js")}}"></script>
        <script>
        $(function () {
            $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
            });
        });

        </script>
    </body>
</html>