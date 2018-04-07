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
                {{ $data['activity']->activity_title }}
            </h1>
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="/activity">Activities</a></li>
                <li class="active">{{ $data['activity']->activity_title }}</li>
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
                                    <td style="width: 30%"><b>Activity Title</b></td>
                                    <td style="width: 70%">{{ $data['activity']->activity_title }}</td>
                                </tr>

                                <tr>
                                    <td><b>Date</b></td>
                                    <td>{{ Carbon::parse($data['activity']->activity_date)->format('d M Y') }}</td>
                                </tr>

                                <tr>
                                    <td><b>Start Time</b></td>
                                    <td>{{ Carbon::parse($data['activity']->start_time)->format('h:i A') }}</td>
                                </tr>

                                <tr>
                                    <td><b>End Time</b></td>
                                    <td>{{ Carbon::parse($data['activity']->end_time)->format('h:i A') }}</td>
                                </tr>

                                <tr>
                                    <td style="width: 30%"><b>Assembly Point</b></td>
                                    <td style="width: 70%">{{ $data['activity']->assembly_point }}</td>
                                </tr>

                                <tr>
                                    <td><b>Who Can Join This Activity</b></td>
                                
                                    @if ($data['activity']->access  == 'R')
                                        <td>Regular</td>
                                    @elseif($data['activity']->access  == 'N')
                                        <td>Newbie</td>
                                    @else
                                        <td>Regular, Newbie</td>
                                    @endif
                                </tr>

                                <tr>
                                    <td><b>Slot</b></td>
                                    <td>{{ $data['activity']->slot }}</td>
                                </tr>

                                <tr>
                                    <td><b>Description</b></td>
                                
                                    @if ($data['activity']->description  == null)
                                        <td>-</td>
                                    @else
                                        <td>{!! $data['activity']->description !!}</td>
                                    @endif
                                </tr>

                                <tr>
                                    <td><b>Remark</b></td>
                                
                                    @if ($data['activity']->remark  == null)
                                        <td>-</td>
                                    @else
                                        <td>{!! $data['activity']->remark !!}</td>
                                    @endif
                                </tr>

                                <tr>
                                    <td><b>Creator</b></td>
                                    <td><a href="/user/staff/{{ $data['activity']->created_by }}/profile">{{ $data['activity']->creator }}</a></td>
                                </tr>

                            </table>
                        </div>

                        <?php
                            $show_action = true;

                            if(Carbon::today() >= Carbon::parse($data['activity']->activity_date)) {
                                $show_action = false;
                            }
                            
                            if(AppHelper::currentUserRole() == "Staff" && Auth::user()->user_id != $data['activity']->created_by) {
                                $show_action = false;
                            }
                        ?>

                        <!-- footer -->
                        <div class="box-footer">
                            @if ($show_action)
                                {!! Form::open(['action' => ['ActivityController@update', $data['activity']->activity_id, 'cancel'], 'onsubmit' => 'return confirmMsg("cancel");', 'method' => 'POST']) !!}

                                    {{Form::hidden('_method', 'PUT')}}
                                    {{ Form::button('<i class="fa fa-times"></i><span> Cancel Activity</span>', ['type' => 'submit', 'class' => 'btn btn-danger pull-left'] )  }}

                                {!! Form::close() !!}

                                <a href="/activity/{{ $data['activity']->activity_id }}/edit" class="btn btn-primary pull-right"><i class="fa fa-edit"></i><span> Edit</span></a>
                            @endif

                        </div>   
                    </div>
                </div>
            </div>    
        </section>

        <script type="text/javascript">
            function confirmMsg(type) {
                var msg;
            
                if(type == "cancel") {
                    msg = "Are you sure to cancel this activity?";
                }

                return confirm(msg);
            }
        </script>
    @endsection
@endif