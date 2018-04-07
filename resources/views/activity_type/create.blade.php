@if (Auth::guest())
    <script type="text/javascript">
        window.location = "{{ route('login') }}";
    </script>
@else
    <!-- do not allow staff to come this page -->
    @if (AppHelper::currentUserRole() == "Staff")
        <script type="text/javascript">
            window.location = "{{ route('activity.index') }}";
        </script>
    @else
        @extends('layouts.app')

        @section('content')
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Add Template
                </h1>
                <ol class="breadcrumb">
                    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="/activity_type">Activity Templates</a></li>
                    <li class="active">Add Template</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content container-fluid">
                <br>
                <div class="row">
                    {{-- <div class="col-md-6 col-md-offset-3"> --}}
                    <div class="col-md-12">
                        <div class="box">
                            
                            {!! Form::open(['action' => ['ActivityTypeController@store'], 'method' => 'POST']) !!}

                                <!-- box body -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- template name -->
                                            <div class="form-group has-feedback {{ $errors->has('activity_type_name') ? ' has-error' : '' }}">
                                                {{Form::label('activity_type_name', 'Template Name <span class="text-danger">*</span>', [], false)}}
                                                {{Form::text('activity_type_name', old('activity_type_name'), ['class' => 'form-control', 'placeholder' => 'Enter template name', 'maxLength' => 100])}}
        
                                                @if ($errors->has('activity_type_name'))
                                                    <span class="help-block">
                                                        *{{ $errors->first('activity_type_name') }}
                                                    </span>
                                                @endif
                                            </div>
        
                                            <!-- activity title -->
                                            <div class="form-group has-feedback {{ $errors->has('activity_title') ? ' has-error' : '' }}">
                                                {{Form::label('activity_title', 'Activity Title <span class="text-danger">*</span>', [], false)}}
                                                {{Form::text('activity_title', old('activity_title'), ['class' => 'form-control', 'placeholder' => 'Enter activity title', 'maxLength' => 100])}}
        
                                                @if ($errors->has('activity_title'))
                                                    <span class="help-block">
                                                        *{{ $errors->first('activity_title') }}
                                                    </span>
                                                @endif
                                            </div>
        
                                            <div class="row">
                                                <!-- start time -->
                                                <div class="bootstrap-timepicker col-md-6">
                                                    <div class="form-group has-feedback {{ $errors->has('start_time') ? ' has-error' : '' }}">
                                                        {{Form::label('start_time', 'Start Time <span class="text-danger">*</span>', [], false)}}
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                            </div>
                                                            {{Form::text('start_time', Carbon::parse("8:00 AM")->format('h:i A'), ['class' => 'form-control timepicker', 'placeholder' => 'Enter start time'])}}
                                                        </div>
                    
                                                        @if ($errors->has('start_time'))
                                                            <span class="help-block">
                                                                *{{ $errors->first('start_time') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- end time -->
                                                <div class="bootstrap-timepicker col-md-6">
                                                    <div class="form-group has-feedback {{ $errors->has('end_time') ? ' has-error' : '' }}">
                                                        {{Form::label('end_time', 'End Time <span class="text-danger">*</span>', [], false)}}
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                            </div>
                                                            {{Form::text('end_time', Carbon::parse("10:00 AM")->format('h:i A'), ['class' => 'form-control timepicker', 'placeholder' => 'Enter end time'])}}
                                                        </div>
                    
                                                        @if ($errors->has('end_time'))
                                                            <span class="help-block">
                                                                *{{ $errors->first('end_time') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- assembly point -->
                                            <div class="form-group has-feedback {{ $errors->has('assembly_point') ? ' has-error' : '' }}">
                                                {{Form::label('assembly_point', 'Assembly Point <span class="text-danger">*</span>', [], false)}}
                                                {{Form::text('assembly_point', old('assembly_point'), ['class' => 'form-control', 'placeholder' => 'Enter assembly point', 'maxLength' => 255])}}
        
                                                @if ($errors->has('assembly_point'))
                                                    <span class="help-block">
                                                        *{{ $errors->first('assembly_point') }}
                                                    </span>
                                                @endif
                                            </div>
        
                                            <!--access -->
                                            <div class="form-group has-feedback {{ $errors->has('access') ? ' has-error' : '' }}">
                                                {{Form::label('access', 'Who Can Join This Activity <span class="text-danger">*</span>', [], false)}}
                                                {{Form::select('access', ['R' => 'Regular', 'N' => 'Newbie', 'B' => 'Both'], 'B', ['class' => 'form-control'])}}
        
                                                @if ($errors->has('access'))
                                                    <span class="help-block">
                                                        *{{ $errors->first('access') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- description -->
                                            <div class="form-group has-feedback {{ $errors->has('description') ? ' has-error' : '' }}">
                                                {{Form::label('description', 'Description')}}
                                                {{Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => 'Enter description (Optional)', 'maxLength' => 1000, 'rows' => 3])}}
        
                                                @if ($errors->has('description'))
                                                    <span class="help-block">
                                                        *{{ $errors->first('description') }}
                                                    </span>
                                                @endif
                                            </div>
        
                                            <!-- remark -->
                                            <div class="form-group has-feedback {{ $errors->has('remark') ? ' has-error' : '' }}">
                                                {{Form::label('remark', 'Remark')}}
                                                {{Form::textarea('remark', old('remark'), ['class' => 'form-control', 'placeholder' => 'Enter remark (Optional)', 'maxLength' => 1000, 'rows' => 3])}}
        
                                                @if ($errors->has('remark'))
                                                    <span class="help-block">
                                                        *{{ $errors->first('remark') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- box footer -->
                                <div class="box-footer">
                                    <a href="/activity_type" class="btn btn-warning pull-left">Cancel</a>
                                    {{Form::submit('Submit', ['class' => 'btn btn-primary pull-right'])}}
                                </div>
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </section>
        @endsection

        @section('js')
            <script>
                $(function () {
                    CKEDITOR.replace('description');
                    CKEDITOR.replace('remark');
                    CKEDITOR.config.height = '120px';
                })
            </script>
        @endsection
    @endif
    
@endif