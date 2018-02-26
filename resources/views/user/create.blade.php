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
                Add Staff
            </h1>
            {{--  <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol>  --}}
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            <br>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="box">
                        
                        {!! Form::open(['action' => ['UserController@store', $type], 'method' => 'POST']) !!}

                            <!-- box body -->
                            <div class="box-body">

                                <!-- full name -->
                                <div class="form-group col-md-12 has-feedback {{ $errors->has('full_name') ? ' has-error' : '' }}">
                                    {{Form::label('full_name', 'Full Name')}}
                                    {{Form::text('full_name', old('full_name'), ['class' => 'form-control', 'placeholder' => 'Enter full name', 'maxLength' => 100])}}

                                    @if ($errors->has('full_name'))
                                        <span class="help-block">
                                            *{{ $errors->first('full_name') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- email -->
                                <div class="form-group col-md-12 has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                    {{Form::label('email', 'Email')}}
                                    {{Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Enter email', 'maxLength' => 100])}}

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            *{{ $errors->first('email') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- email confirmation -->
                                <div class="form-group col-md-12 has-feedback {{ $errors->has('email_confirmation') ? ' has-error' : '' }}">
                                    {{Form::label('email_confirmation', 'Confirm Email')}}
                                    {{Form::text('email_confirmation', old('email_confirmation'), ['class' => 'form-control', 'placeholder' => 'Confirm email', 'maxLength' => 100])}}

                                    @if ($errors->has('email_confirmation'))
                                        <span class="help-block">
                                            *{{ $errors->first('email_confirmation') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="form-row">
                                    <!-- ic passport -->
                                    <div class="form-group col-md-6 has-feedback {{ $errors->has('ic_passport') ? ' has-error' : '' }}">
                                        {{Form::label('ic_passport', 'IC / Passport No.')}}
                                        {{Form::text('ic_passport', old('ic_passport'), ['class' => 'form-control', 'placeholder' => 'Enter IC or passport no.', 'maxLength' => 20])}}
                                        <small class="text-warning">This field will be used as the default password.</small>
    
                                        @if ($errors->has('ic_passport'))
                                            <span class="help-block">
                                                *{{ $errors->first('ic_passport') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- ic passport confirmation -->
                                    <div class="form-group col-md-6 has-feedback {{ $errors->has('ic_passport_confirmation') ? ' has-error' : '' }}">
                                        {{Form::label('ic_passport_confirmation', 'Confirm IC / Passport No.')}}
                                        {{Form::text('ic_passport_confirmation', old('ic_passport_confirmation'), ['class' => 'form-control', 'placeholder' => 'Confirm IC or passport no.', 'maxLength' => 20])}}
                                        <small class="text-warning">This field <b>CANNOT</b> be changed later.</small>
    
                                        @if ($errors->has('ic_passport_confirmation'))
                                            <span class="help-block">
                                                *{{ $errors->first('ic_passport_confirmation') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row">
                                    <!-- gender -->
                                    <div class="form-group col-md-6 has-feedback {{ $errors->has('gender') ? ' has-error' : '' }}">
                                        {{Form::label('gender', 'Gender')}}
                                        {{Form::select('gender', ['M' => 'Male', 'F' => 'Female'], Auth::user()->gender, ['class' => 'form-control'])}}
    
                                        @if ($errors->has('gender'))
                                            <span class="help-block">
                                                *{{ $errors->first('gender') }}
                                            </span>
                                        @endif
                                    </div>  

                                    <!-- dob -->
                                    <div class="form-group col-md-6 has-feedback {{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
                                        {{Form::label('date_of_birth', 'Date of Birth')}}
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            {{Form::text('date_of_birth', Carbon::parse(Carbon::now())->format('d M Y'), ['class' => 'form-control pull-right', 'id' => 'datepicker'])}}
                                        </div>

                                        @if ($errors->has('date_of_birth'))
                                            <span class="help-block">
                                                *{{ $errors->first('date_of_birth') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row">
                                    <!-- contact no-->
                                    <div class="form-group col-md-6 has-feedback {{ $errors->has('phone_no') ? ' has-error' : '' }}">
                                        {{Form::label('phone_no', 'Contact No.')}}
                                        {{Form::text('phone_no', old('phone_no'), ['class' => 'form-control', 'placeholder' => 'Enter contact no.', 'maxLength' => 20])}}
    
                                        @if ($errors->has('phone_no'))
                                            <span class="help-block">
                                                *{{ $errors->first('phone_no') }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- user type -->
                                    @if (Auth::user()->usertype == 1)
                                        <div class="form-group col-md-6 has-feedback {{ $errors->has('usertype') ? ' has-error' : '' }}">
                                            {{Form::label('usertype', 'User Type')}}
                                            {{Form::select('usertype', ['2' => 'Admin', '3' => 'Staff'], '3', ['class' => 'form-control'])}}
        
                                            @if ($errors->has('usertype'))
                                                <span class="help-block">
                                                    *{{ $errors->first('usertype') }}
                                                </span>
                                            @endif
                                        </div>  
                                    @endif
                                </div>
                            </div>

                            <!-- box footer -->
                            <div class="box-footer">
                                <a href="/user/staff" class="btn btn-warning pull-left">Cancel</a>
                                {{Form::submit('Submit', ['class' => 'btn btn-primary pull-right'])}}
                            </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </section>
    @endsection
@endif