@if (Auth::guest())
    <script type="text/javascript">
        window.location = "{{ route('login') }}";
    </script>
@else
    @extends('layouts.app')

    @section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Reset Password
            </h1>
            
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="/profile">My Profile</a></li>
                <li class="active">Reset Password</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            <br>
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="box">
                        
                        {!! Form::open(['action' => ['ProfileController@updatePassword', Auth::user()->user_id], 'method' => 'POST']) !!}

                            <!-- box body -->
                            <div class="box-body">

                                <!-- current password -->
                                <div class="form-group has-feedback {{ session('error') ? ' has-error' : '' }}">
                                    {{Form::label('current_password', 'Current Password')}}
                                    {{Form::password('current_password', ['class' => 'form-control', 'placeholder' => 'Enter your current password', 'maxLength' => 20])}}

                                    @if (session('error'))
                                        <span class="help-block">
                                            *{{ session('error') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- new password -->
                                <div class="form-group has-feedback {{ $errors->has('new_password') ? ' has-error' : '' }}">
                                    {{Form::label('new_password', 'New Password')}}
                                    {{Form::password('new_password', ['class' => 'form-control', 'placeholder' => 'Enter new password', 'maxLength' => 20, 'id' => 'password'])}}

                                    @if ($errors->has('new_password'))
                                        <span class="help-block">
                                            *{{ $errors->first('new_password') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- new password confirmation -->
                                <div class="form-group has-feedback {{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                                        {{Form::label('new_password_confirmation', 'Confirm Password')}}
                                        {{Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm new password', 'maxLength' => 20])}}
    
                                        @if ($errors->has('new_password_confirmation'))
                                            <span class="help-block">
                                                *{{ $errors->first('new_password_confirmation') }}
                                            </span>
                                        @endif
                                    </div>
                            </div>

                            <!-- box footer -->
                            <div class="box-footer">
                                {{Form::hidden('_method', 'PUT')}}
                                <a href="/profile" class="btn btn-warning pull-left">Cancel</a>
                                {{Form::submit('Submit', ['class' => 'btn btn-primary pull-right'])}}
                            </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
            
        </section>
        <!-- /.content -->
    @endsection
@endif

