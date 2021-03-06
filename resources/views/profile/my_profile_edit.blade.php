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
                Edit My Profile
            </h1>
            
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="/profile">My Profile</a></li>
                <li class="active">Edit</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            <img src="/storage/profile_image/{{ Auth::user()->profile_image }}" class="profile-user-img img-responsive" alt="User Image" />
            <br>
            
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="box">
                        
                        {!! Form::open(['action' => ['ProfileController@update', Auth::user()->user_id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                            <!-- box body -->
                            <div class="box-body">
                                <!-- profile image -->
                                <div class="form-group has-feedback {{ $errors->has('profile_image') ? ' has-error' : '' }}">
                                    {{Form::label('profile_image', 'Profile Image')}}
                                    {{Form::file('profile_image')}}

                                    @if ($errors->has('profile_image'))
                                        <span class="help-block">
                                            *{{ $errors->first('profile_image') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- profile name -->
                                <div class="form-group has-feedback {{ $errors->has('profile_name') ? ' has-error' : '' }}">
                                    {{Form::label('profile_name', 'Profile Name <span class="text-danger">*</span>', [], false)}}
                                    {{Form::text('profile_name', Auth::user()->profile_name, ['class' => 'form-control', 'placeholder' => 'Enter your profile name', 'maxLength' => 100])}}

                                    @if ($errors->has('profile_name'))
                                        <span class="help-block">
                                            *{{ $errors->first('profile_name') }}
                                        </span>
                                    @endif
                                </div>
            
                                <!-- full name -->
                                <div class="form-group has-feedback {{ $errors->has('full_name') ? ' has-error' : '' }}">
                                    {{Form::label('full_name', 'Full Name <span class="text-danger">*</span>', [], false)}}
                                    {{Form::text('full_name', Auth::user()->full_name, ['class' => 'form-control', 'placeholder' => 'Enter your full name', 'maxLength' => 100])}}

                                    @if ($errors->has('full_name'))
                                        <span class="help-block">
                                            *{{ $errors->first('full_name') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="row">
                                    <!-- gender -->
                                    <div class="form-group col-md-6 has-feedback {{ $errors->has('gender') ? ' has-error' : '' }}">
                                        {{Form::label('gender', 'Gender <span class="text-danger">*</span>', [], false)}}
                                        {{Form::select('gender', ['M' => 'Male', 'F' => 'Female'], Auth::user()->gender, ['class' => 'form-control'])}}
    
                                        @if ($errors->has('gender'))
                                            <span class="help-block">
                                                *{{ $errors->first('gender') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- date of birth -->
                                    <div class="form-group col-md-6 has-feedback {{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
                                        {{Form::label('date_of_birth', 'Date of Birth <span class="text-danger">*</span>', [], false)}}
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            {{Form::text('date_of_birth', Carbon::parse(Auth::user()->date_of_birth)->format('d M Y'), ['class' => 'form-control pull-right', 'id' => 'datepicker', 'placeholder' => 'Enter date of birth'])}}
                                        </div>
    
                                        @if ($errors->has('date_of_birth'))
                                            <span class="help-block">
                                                *{{ $errors->first('date_of_birth') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- phone no -->
                                <div class="form-group has-feedback {{ $errors->has('phone_no') ? ' has-error' : '' }}">
                                    {{Form::label('phone_no', 'Contact No. <span class="text-danger">*</span>', [], false)}}
                                    {{Form::text('phone_no', Auth::user()->phone_no, ['class' => 'form-control', 'placeholder' => 'Enter your contact no.', 'maxLength' => 20])}}

                                    @if ($errors->has('phone_no'))
                                        <span class="help-block">
                                            *{{ $errors->first('phone_no') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- address -->
                                <div class="form-group has-feedback {{ $errors->has('address') ? ' has-error' : '' }}">
                                    {{Form::label('address', 'Address <span class="text-danger">*</span>', [], false)}}
                                    {{Form::text('address', Auth::user()->address, ['class' => 'form-control', 'placeholder' => 'Enter your address.', 'maxLength' => 255])}}

                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                            *{{ $errors->first('address') }}
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

