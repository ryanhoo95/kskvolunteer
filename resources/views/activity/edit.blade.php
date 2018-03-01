@if (Auth::guest())
    <script type="text/javascript">
        window.location = "{{ route('login') }}";
    </script>
@else
    <!-- do not allow staff to edit activity created by others -->
    @if (AppHelper::currentUserRole() == "Staff" && Auth::user()->user_id != $data['activity']->created_by)
        <script type="text/javascript">
            window.location = "{{ route('activity.index') }}";
        </script>
    @else
        @extends('layouts.app')

        @section('content')
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Edit Activity
                </h1>
                <ol class="breadcrumb">
                    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="/activity">Activities</a></li>
                    <li><a href="/activity/{{ $data['activity']->activity_id }}">{{ $data['activity']->activity_title }}</a></li>
                    <li class="active">Edit</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content container-fluid">
                <br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="box">
                            
                            {!! Form::open(['action' => ['ActivityController@update', $data['activity']->activity_id, 'update_info'], 'method' => 'POST']) !!}

                                <!-- box body -->
                                <div class="box-body">

                                    <!-- activity title -->
                                    <div class="form-group has-feedback {{ $errors->has('activity_title') ? ' has-error' : '' }}">
                                        {{Form::label('activity_title', 'Activity Title')}}
                                        {{Form::text('activity_title', $data['activity']->activity_title, ['class' => 'form-control', 'placeholder' => 'Enter activity title', 'maxLength' => 100])}}

                                        @if ($errors->has('activity_title'))
                                            <span class="help-block">
                                                *{{ $errors->first('activity_title') }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <!-- slot -->
                                        <div class="col-md-6">
                                            <div class="form-group has-feedback {{ $errors->has('slot') ? ' has-error' : '' }}">
                                                {{Form::label('slot', 'Slot')}}
                                                {{Form::text('slot', $data['activity']->slot, ['class' => 'form-control', 'placeholder' => 'Enter number of slot', 'maxLength' => 3])}}
            
                                                @if ($errors->has('slot'))
                                                    <span class="help-block">
                                                        *{{ $errors->first('slot') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
    
                                        <!-- date -->
                                        <div class="col-md-6">
                                            <div class="form-group has-feedback {{ $errors->has('date') ? ' has-error' : '' }}">
                                                {{Form::label('date', 'Date')}}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    {{Form::text('date', Carbon::parse($data['activity']->activity_date)->format('d M Y'), ['class' => 'form-control pull-right', 'placeholder' => 'Enter date', 'id' => 'datepicker_min'])}}
                                                </div>
            
                                                @if ($errors->has('date'))
                                                    <span class="help-block">
                                                        *{{ $errors->first('date') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
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
                                                    {{Form::text('start_time', Carbon::parse($data['activity']->start_time)->format('h:i A'), ['class' => 'form-control timepicker', 'placeholder' => 'Enter start time'])}}
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
                                                    {{Form::text('end_time', Carbon::parse($data['activity']->end_time)->format('h:i A'), ['class' => 'form-control timepicker', 'placeholder' => 'Enter end time'])}}
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
                                        {{Form::textarea('description', $data['activity']->description, ['class' => 'form-control', 'placeholder' => 'Enter description (Optional)', 'maxLength' => 1000, 'rows' => 3])}}

                                        @if ($errors->has('description'))
                                            <span class="help-block">
                                                *{{ $errors->first('description') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- remark -->
                                    <div class="form-group has-feedback {{ $errors->has('remark') ? ' has-error' : '' }}">
                                        {{Form::label('remark', 'Remark')}}
                                        {{Form::textarea('remark', $data['activity']->remark, ['class' => 'form-control', 'placeholder' => 'Enter remark (Optional)', 'maxLength' => 1000, 'rows' => 3])}}

                                        @if ($errors->has('remark'))
                                            <span class="help-block">
                                                *{{ $errors->first('remark') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- box footer -->
                                <div class="box-footer">
                                    <a href="/activity/{{ $data['activity']->activity_id }}" class="btn btn-warning pull-left">Cancel</a>
                                    {{Form::hidden('_method', 'PUT')}}
                                    {{Form::submit('Submit', ['class' => 'btn btn-primary pull-right'])}}
                                </div>
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </section>
        @endsection
    @endif
    
@endif