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
                    Edit Template
                </h1>
                <ol class="breadcrumb">
                    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="/activity_type">Activity Templates</a></li>
                    <li><a href="/activity_type/{{ $data['activity_type']->activity_type_id }}">{{ $data['activity_type']->activity_title }}</a></li>
                    <li class="active">Edit</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content container-fluid">
                <br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="box">
                            
                            {!! Form::open(['action' => ['ActivityTypeController@update', $data['activity_type']->activity_type_id, 'update_info'], 'method' => 'POST']) !!}

                                <!-- box body -->
                                <div class="box-body">

                                    <!-- activity title -->
                                    <div class="form-group has-feedback {{ $errors->has('activity_title') ? ' has-error' : '' }}">
                                        {{Form::label('activity_title', 'Activity Title')}}
                                        {{Form::text('activity_title', $data['activity_type']->activity_title, ['class' => 'form-control', 'placeholder' => 'Enter activity title', 'maxLength' => 100])}}

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
                                                {{Form::label('start_time', 'Start Time')}}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    {{Form::text('start_time', Carbon::parse($data['activity_type']->start_time)->format('h:i A'), ['class' => 'form-control timepicker', 'placeholder' => 'Enter start time'])}}
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
                                                {{Form::label('end_time', 'End Time')}}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    {{Form::text('end_time', Carbon::parse($data['activity_type']->end_time)->format('h:i A'), ['class' => 'form-control timepicker', 'placeholder' => 'Enter end time'])}}
                                                </div>
            
                                                @if ($errors->has('end_time'))
                                                    <span class="help-block">
                                                        *{{ $errors->first('end_time') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- description -->
                                    <div class="form-group has-feedback {{ $errors->has('description') ? ' has-error' : '' }}">
                                        {{Form::label('description', 'Description')}}
                                        {{Form::textarea('description', $data['activity_type']->description, ['class' => 'form-control', 'placeholder' => 'Enter description (Optional)', 'maxLength' => 1000, 'rows' => 3])}}

                                        @if ($errors->has('description'))
                                            <span class="help-block">
                                                *{{ $errors->first('description') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- remark -->
                                    <div class="form-group has-feedback {{ $errors->has('remark') ? ' has-error' : '' }}">
                                        {{Form::label('remark', 'Remark')}}
                                        {{Form::textarea('remark', $data['activity_type']->remark, ['class' => 'form-control', 'placeholder' => 'Enter remark (Optional)', 'maxLength' => 1000, 'rows' => 3])}}

                                        @if ($errors->has('remark'))
                                            <span class="help-block">
                                                *{{ $errors->first('remark') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- box footer -->
                                <div class="box-footer">
                                    <a href="/activity_type/{{ $data['activity_type']->activity_type_id }}" class="btn btn-warning pull-left">Cancel</a>
                                    {{Form::hidden('_method', 'PUT')}}
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
                })
            </script>
        @endsection
    @endif
    
@endif