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
                Add Activity
            </h1>
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="/activity">Activities</a></li>
                <li class="active">Add Activity</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            <br>
            <div class="row">
                {{-- <div class="col-md-6 col-md-offset-3"> --}}
                <div class="col-md-12">
                    <div class="box">
                        
                        {!! Form::open(['action' => ['ActivityController@store'], 'method' => 'POST']) !!}

                            <!-- box body -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        @if (count($data['activity_types']) != 0) 
                                            <div class="form-group">
                                                {{Form::label('template', 'Select Template')}}
                                                <select name="template" id="select_template", class="form-control" onchange="changeTemplate()">
                                                    <option value="default">Default (Blank)</option>
                                                    @foreach ($data['activity_types'] as $activity_type)
                                                        <option value="{{ $activity_type->activity_type_name }}">{{ $activity_type->activity_type_name }}</option>        
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                        @endif
        
                                        <!-- activity title -->
                                        <div class="form-group has-feedback {{ $errors->has('activity_title') ? ' has-error' : '' }}">
                                            {{Form::label('activity_title', 'Activity Title <span class="text-danger">*</span>', [], false)}}
                                            {{Form::text('activity_title', old('activity_title'), ['class' => 'form-control', 'placeholder' => 'Enter activity title', 'maxLength' => 100, 'id' => 'activity_title'])}}
        
                                            @if ($errors->has('activity_title'))
                                                <span class="help-block">
                                                    *{{ $errors->first('activity_title') }}
                                                </span>
                                            @endif
                                        </div>

                                        <!--access -->
                                        <div class="form-group has-feedback {{ $errors->has('access') ? ' has-error' : '' }}">
                                            {{Form::label('access', 'Who Can Join This Activity <span class="text-danger">*</span>', [], false)}}
                                            {{Form::select('access', ['R' => 'Regular', 'N' => 'Newbie', 'B' => 'Both'], 'B', ['class' => 'form-control', 'id' => 'access'])}}
    
                                            @if ($errors->has('access'))
                                                <span class="help-block">
                                                    *{{ $errors->first('access') }}
                                                </span>
                                            @endif
                                        </div>
        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group has-feedback {{ $errors->has('slot') ? ' has-error' : '' }}">
                                                    {{Form::label('slot', 'Slot <span class="text-danger">*</span>', [], false)}}
                                                    {{Form::text('slot', old('slot'), ['class' => 'form-control', 'placeholder' => 'Enter number of slot', 'maxLength' => 3, 'id' => 'slot'])}}
                
                                                    @if ($errors->has('slot'))
                                                        <span class="help-block">
                                                            *{{ $errors->first('slot') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
        
                                            <div class="col-md-6">
                                                <div class="form-group has-feedback {{ $errors->has('date') ? ' has-error' : '' }}">
                                                    {{Form::label('date', 'Date <span class="text-danger">*</span>', [], false)}}
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        {{Form::text('date', old('date'), ['class' => 'form-control pull-right', 'placeholder' => 'Enter date', 'id' => 'date_range'])}}
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
                                                    {{Form::label('start_time', 'Start Time <span class="text-danger">*</span>', [], false)}}
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                        {{Form::text('start_time', Carbon::parse("8:00 AM")->format('h:i A'), ['class' => 'form-control timepicker', 'placeholder' => 'Enter start time', 'id' => 'start_time'])}}
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
                                                        {{Form::text('end_time', Carbon::parse("10:00 AM")->format('h:i A'), ['class' => 'form-control timepicker', 'placeholder' => 'Enter end time', 'id' => 'end_time'])}}
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
                                            {{Form::text('assembly_point', old('assembly_point'), ['class' => 'form-control', 'placeholder' => 'Enter assembly point', 'maxLength' => 255, 'id' => 'assembly_point'])}}
    
                                            @if ($errors->has('assembly_point'))
                                                <span class="help-block">
                                                    *{{ $errors->first('assembly_point') }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- enquiry person -->
                                        <div class="form-group has-feedback {{ $errors->has('enquiry_person') ? ' has-error' : '' }}">
                                            {{Form::label('enquiry_person', 'Enquiry Persons <span class="text-danger">*</span>', [], false)}}
                                            {{Form::select('enquiry_person[]', $data['staffs'], Auth::user()->user_id, ['class' => 'form-control select2', 'multiple' => 'multiple', 'data-placeholder' => 'Select enquiry person'])}}
    
                                            @if ($errors->has('enquiry_person'))
                                                <span class="help-block">
                                                    *{{ $errors->first('enquiry_person') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- description -->
                                        <div class="form-group has-feedback {{ $errors->has('description') ? ' has-error' : '' }}">
                                            {{Form::label('description', 'Description')}}
                                            {{Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => 'Enter description (Optional)', 'maxLength' => 1000, 'rows' => 3, 'id' => 'description'])}}
        
                                            @if ($errors->has('description'))
                                                <span class="help-block">
                                                    *{{ $errors->first('description') }}
                                                </span>
                                            @endif
                                        </div>
        
                                        <!-- remark -->
                                        <div class="form-group has-feedback {{ $errors->has('remark') ? ' has-error' : '' }}">
                                            {{Form::label('remark', 'Remark')}}
                                            {{Form::textarea('remark', old('remark'), ['class' => 'form-control', 'placeholder' => 'Enter remark (Optional)', 'maxLength' => 1000, 'rows' => 3, 'id' => 'remark'])}}
        
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
                                <a href="/activity" class="btn btn-warning pull-left">Cancel</a>
                                {{Form::submit('Submit', ['class' => 'btn btn-primary pull-right'])}}
                            </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </section>

        <!-- js function -->
        <script type="text/javascript">
            //get the data returend
            var rawData = '<?php echo json_encode($data, true); ?>';
            //var data = JSON.parse('<?php echo json_encode($data); ?>');
            //rawData = rawData.replace("\\\"", "\\\\\"");
            // var rawObject = encodeURI(rawData);
            console.log(rawData);
            var data = JSON.parse(rawData);
            console.log(data);

            var selected_template = document.getElementById("select_template");
            var activity_title = document.getElementById("activity_title");
            var access = document.getElementById("access");
            var start_time = document.getElementById("start_time");
            var end_time = document.getElementById("end_time");
            var assembly_point = document.getElementById("assembly_point");
            var description = document.getElementById("description");
            var remark = document.getElementById("remark");

            function changeTemplate() {
                if (selected_template.value == "default"){
                    activity_title.value = null;
                    access.value = 'B';
                    start_time.value = "08:00 AM";
                    end_time.value = "10:00 AM";
                    assembly_point.value = null;
                    // description.value = null;
                    // remark.value = null;
                    CKEDITOR.instances['description'].setData(null);
                    CKEDITOR.instances['remark'].setData(null);
                }     
                else{
                    for(var i = 0; i < data.activity_types.length; i++) {
                        if(data.activity_types[i].activity_type_name == selected_template.value) {
                            activity_title.value = data.activity_types[i].activity_title;
                            access.value = data.activity_types[i].access;
                            start_time.value = parseTime(data.activity_types[i].start_time);
                            end_time.value = parseTime(data.activity_types[i].end_time);
                            assembly_point.value = data.activity_types[i].assembly_point;
                            //description.value = data.activity_types[i].description;
                            //remark.value = data.activity_types[i].remark;
                            CKEDITOR.instances['description'].setData(data.activity_types[i].description);
                            CKEDITOR.instances['remark'].setData(data.activity_types[i].remark);
                            break;
                        }
                    }
                }    
            }

            function parseTime(time) {
                var hour = parseInt(time);
                var minute = time.substring(3, 5);

                if(hour > 12) {
                    if((hour - 12) < 10) {
                        return '0' + (hour - 12) + ':' + minute + " PM";
                    }
                    else {
                        return (hour - 12) + ':' + minute + " PM";
                    }
                }
                else {
                    if(hour < 10) {
                        return '0' + hour + ':' + minute + " AM";
                    }
                    else {
                        return hour + ':' + minute + " AM";
                    }
                }
            }
        </script>

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