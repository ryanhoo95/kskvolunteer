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
                    {{ $data['activity_type']->activity_title }}
                </h1>
                <ol class="breadcrumb">
                    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="/activity_type">Activity Templates</a></li>
                    <li class="active">{{ $data['activity_type']->activity_title }}</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        {{ session('success') }}
                    </div>
                @endif

                <br/>

                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="box">
                            <!-- body -->
                            <div class="box-body no-padding">
                                <table class="table">
                                    <tr>
                                        <td style="width: 30%"><b>Template Name</b></td>
                                        <td id="full_name" style="width: 70%">{{ $data['activity_type']->activity_type_name }}</td>
                                    </tr>

                                    <tr>
                                        <td style="width: 30%"><b>Activity Title</b></td>
                                        <td id="full_name" style="width: 70%">{{ $data['activity_type']->activity_title }}</td>
                                    </tr>

                                    <tr>
                                        <td><b>Start Time</b></td>
                                        <td>{{ Carbon::parse($data['activity_type']->start_time)->format('h:i A') }}</td>
                                    </tr>

                                    <tr>
                                        <td><b>End Time</b></td>
                                        <td>{{ Carbon::parse($data['activity_type']->end_time)->format('h:i A') }}</td>
                                    </tr>

                                    <tr>
                                        <td style="width: 30%"><b>Assembly Point</b></td>
                                        <td id="full_name" style="width: 70%">{{ $data['activity_type']->assembly_point }}</td>
                                    </tr>

                                    <tr>
                                        <td><b>Who Can Join This Activity</b></td>
                                    
                                        @if ($data['activity_type']->access  == 'R')
                                            <td>Regular</td>
                                        @elseif($data['activity_type']->access  == 'N')
                                            <td>Newbie</td>
                                        @else
                                            <td>Both</td>
                                        @endif
                                    </tr>

                                    <tr>
                                        <td><b>Description</b></td>
                                    
                                        @if ($data['activity_type']->description  == null)
                                            <td>-</td>
                                        @else
                                            <td>{!! $data['activity_type']->description !!}</td>
                                        @endif
                                    </tr>

                                    <tr>
                                        <td><b>Remark</b></td>
                                    
                                        @if ($data['activity_type']->remark  == null)
                                            <td>-</td>
                                        @else
                                            <td>{!! $data['activity_type']->remark !!}</td>
                                        @endif
                                    </tr>

                                    <tr>
                                        <td><b>Status</b></td>
                                        @if ($data['activity_type']->status == "A")
                                            <td class="text-success"><b>Active</b></td>
                                        @else
                                            <td class="text-danger"><b>Inactive</b></td>
                                        @endif
                                    </tr>
                                </table>
                            </div>

                            <!-- footer -->
                            <div class="box-footer">
                                @if ($data['activity_type']->status == "A")
                                    {!! Form::open(['action' => ['ActivityTypeController@update', $data['activity_type']->activity_type_id, 'deactivate'], 'onsubmit' => 'return confirmMsg("deactivate");', 'method' => 'POST']) !!}

                                        {{Form::hidden('_method', 'PUT')}}
                                        {{ Form::button('<i class="fa fa-thumbs-down"></i><span> Deactivate Template</span>', ['type' => 'submit', 'class' => 'btn btn-danger pull-left'] )  }}

                                    {!! Form::close() !!}
                                @else
                                    {!! Form::open(['action' => ['ActivityTypeController@update', $data['activity_type']->activity_type_id, 'activate'], 'onsubmit' => 'return confirmMsg("activate");', 'method' => 'POST']) !!}

                                        {{Form::hidden('_method', 'PUT')}}
                                        {{ Form::button('<i class="fa fa-thumbs-up"></i><span> Activate Template</span>', ['type' => 'submit', 'class' => 'btn btn-success pull-left'] )  }}

                                    {!! Form::close() !!}
                                @endif

                                <a href="/activity_type/{{ $data['activity_type']->activity_type_id }}/edit" class="btn btn-primary pull-right"><i class="fa fa-edit"></i><span> Edit</span></a>
                            </div>   
                        </div>
                    </div>
                </div>    
            </section>

            <script type="text/javascript">
                function confirmMsg(type) {
                    var msg;
                
                    if(type == "deactivate") {
                        msg = "Are you sure to deactivate this template?";
                    }
                    else if(type == "activate") {
                        msg = "Are you sure to activate this template?";
                    }

                    return confirm(msg);
                }
            </script>
        @endsection
    @endif
@endif