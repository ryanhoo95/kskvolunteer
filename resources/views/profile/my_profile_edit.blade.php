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
        {{--  <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>  --}}
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            <img src="/storage/profile_image/{{ Auth::user()->profileImage }}" class="profile-user-img img-responsive img-circle" alt="User Image" />
            <br>
            
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="box">
                        
                        {!! Form::open(['action' => ['ProfileController@update', Auth::user()->userID], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                            <div class="box-body">
                                <div class="form-group">
                                    {{Form::label('profileImage', 'Profile Image')}}
                                    {{Form::file('cover_image')}}
                                </div>
                                
                                <div class="form-group">
                                    {{Form::label('profileName', 'Profile Name')}}
                                    {{Form::text('title', Auth::user()->profileName, ['class' => 'form-control', 'placeholder' => 'Enter your profile name', 'maxLength' => 100])}}
                                </div>
            
                                <div class="form-group">
                                    {{Form::label('fullName', 'Full Name')}}
                                    {{Form::text('body', Auth::user()->fullName, ['class' => 'form-control', 'placeholder' => 'Enter your full name', 'maxLength' => 100])}}
                                </div>

                                <div class="form-group">
                                    {{Form::label('gender', 'Gender')}}
                                    {{Form::select('gender', ['M' => 'Male', 'F' => 'Female'], Auth::user()->gender, ['class' => 'form-control'])}}
                                </div>

                                <div class="form-group">
                                    {{Form::label('dateOfBirth', 'Date of Birth')}}
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        {{Form::text('dateOfBirth', Carbon::parse(Auth::user()->dateOfBirth)->format('d M Y'), ['class' => 'form-control pull-right', 'id' => 'datepicker'])}}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {{Form::label('phoneNo', 'Contact No.')}}
                                    {{Form::text('phoneNo', Auth::user()->phoneNo, ['class' => 'form-control', 'placeholder' => 'Enter your contact no.', 'maxLength' => 20])}}
                                </div>

                                <div class="form-group">
                                    {{Form::label('address', 'Address')}}
                                    {{Form::text('address', Auth::user()->address, ['class' => 'form-control', 'placeholder' => 'Enter your address.', 'maxLength' => 255])}}
                                </div>
        
                                
                            </div>

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

