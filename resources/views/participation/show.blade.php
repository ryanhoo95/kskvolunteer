@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if ($data['activity'])
                <a href="#modal-activity" data-toggle="modal">
                    {{ $data['activity']->activity_title }}
                </a>
                <br>
                <small>{{ $data['activity']->activity_date }}, {{ $data['activity']->start_time }} - 
                {{ $data['activity']->end_time }}</small>
            @else
                Activity not found.
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            @if ($data['activity'])
                <li><a href="/participation?date={{ $data['activity']->activity_date }}">Participation</a></li>
                <li class="active">{{ $data['activity']->activity_title }}</li>
            @endif
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

        @if ($data['activity'])
            <p>
                <span>
                    <b><mark class="text-warning">Name</mark></b>
                </span>
                 - Newbie
            </p>
            <div class="row">
                <div class="col-md-6">
                    <!-- for vips -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-success">
                                <!-- header -->
                                <div class="box-header text-center">
                                    <h3 class="box-title pull-left"> <b>VIP</b></h3>
                                    @if (Carbon::today() <= Carbon::parse($data['activity']->activity_date))
                                        <a href="/participation/{{$data['activity']->activity_id}}/vip/create" class="btn btn-success pull-right"><i class="fa fa-plus"></i><span> Add VIP</span></a>
                                    @endif
                                </div>
        
                                <!-- body -->
                                <div class="box-body table-responsive">
                                    @if (count($data['vips']) == 0)
                                        <p class="text-danger">There is no VIP for this activity.</p>
                                    @else
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <th>Name</th>
                                                <th>Remark</th>
                                                <th>Action</th>
                                            </thead>

                                            <tbody>
                                                @foreach ($data['vips'] as $vip)
                                                    <tr>
                                                        <td style="width: 25%">{{ $vip->participant_name }}</td>
                                                        <td style="width: 40%">{{ $vip->participant_remark }}</td>
                                                        @if (Carbon::today() <= Carbon::parse($data['activity']->activity_date))
                                                            <td style="width: 35%">
                                                                {!! Form::open(['action' => ['ParticipationController@cancelVIP', $data['activity']->activity_id, $vip->participation_id], 'onsubmit' => 'return confirmMsg("cancel", "'.$vip->participant_name.'");', 'method' => 'POST']) !!}

                                                                    {{Form::hidden('_method', 'PUT')}}
                                                                
                                                                    {{ Form::button('<i class="fa fa-trash"></i><span> Cancel</span>', ['type' => 'submit', 'class' => 'btn btn-danger pull-left', 'style' => 'margin-right:5px'] )  }}
                    
                                                                {!! Form::close() !!}

                                                                <a href="/participation/{{ $data['activity']->activity_id }}/vip/{{ $vip->participation_id }}/edit" class="btn btn-primary"><i class="fa fa-edit"></i><span> Edit</span></a>
                                                            </td>
                                                        @else
                                                            <td style="width: 35%">None</td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @foreach ($data['vips'] as $vip)
                                            
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- for individuals -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <!-- header -->
                                <div class="box-header text-center">
                                    <h3 class="box-title pull-left"> <b>Individual</b></h3>
                                </div>
        
                                <!-- body -->
                                <div class="box-body table-responsive">
                                    @if (count($data['individuals']) == 0)
                                        <p class="text-danger">There is no individual participation for this activity.</p>
                                    @else
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <th>Name</th>
                                                <th>IC / Passport No.</th>
                                                <th>Attendance</th>
                                            </thead>

                                            <tbody>
                                                @foreach ($data['individuals'] as $individual)
                                                    <tr>
                                                        <td style="width: 35%">
                                                            @if ($individual->category == "Newbie")
                                                                <a href="#modal-participant" class="text-warning" data-toggle="modal" data-id="{{ $individual->user_id}}">
                                                                    <b><mark class="text-warning">{{ $individual->full_name }}</mark></b>
                                                                </a>
                                                            @else
                                                                <a href="#modal-participant" data-toggle="modal" data-id="{{ $individual->user_id}}">
                                                                    {{ $individual->full_name }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td style="width: 25%">{{ $individual->ic_passport }}</td>
                                                        <td style="width: 40%">
                                                            @if ($individual->status == "J")
                                                                
                                                                {!! Form::open(['action' => ['ParticipationController@absent', $data['activity']->activity_id, $individual->participation_id], 'onsubmit' => 'return confirmMsg("absent", "'.$individual->full_name.'");', 'method' => 'POST']) !!}

                                                                    {{Form::hidden('_method', 'PUT')}}
                                                                    @if (Carbon::today() < Carbon::parse($data['activity']->activity_date))
                                                                        {{ Form::button('<i class="fa fa-thumbs-down"></i><span> Absent</span>', ['class' => 'btn btn-danger pull-left disabled', 'style' => 'margin-right:5px'] )  }}
                                                                    @else
                                                                        {{ Form::button('<i class="fa fa-thumbs-down"></i><span> Absent</span>', ['type' => 'submit', 'class' => 'btn btn-danger pull-left', 'style' => 'margin-right:5px'] )  }}
                                                                    @endif
                        
                                                                {!! Form::close() !!}
                                                                
                                                                {!! Form::open(['action' => ['ParticipationController@present', $data['activity']->activity_id, $individual->participation_id], 'onsubmit' => 'return confirmMsg("present", "'.$individual->full_name.'");', 'method' => 'POST']) !!}

                                                                    {{Form::hidden('_method', 'PUT')}}
                                                                    @if (Carbon::today() < Carbon::parse($data['activity']->activity_date))
                                                                        {{ Form::button('<i class="fa fa-thumbs-up"></i><span> Present</span>', ['class' => 'btn btn-success pull-left disabled'] )  }}
                                                                    @else
                                                                        {{ Form::button('<i class="fa fa-thumbs-up"></i><span> Present</span>', ['type' => 'submit', 'class' => 'btn btn-success pull-left'] )  }}
                                                                    @endif
                        
                                                                {!! Form::close() !!}
    
                                                            @elseif($individual->status == "A")
                                                                <span class="text-danger"><i class="fa fa-thumbs-down"></i><span> Absent</span>
    
                                                            @elseif($individual->status == "P")
                                                                <span class="text-success"><i class="fa fa-thumbs-up"></i><span> Present</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- for group -->
                <div class="col-md-6">
                    <div class="box box-info">
                        <!-- header -->
                        <div class="box-header text-center">
                            <h3 class="box-title pull-left"> <b>Group</b></h3>
                        </div>

                        <!-- body -->
                        <div class="box-body table-responsive">
                            @if (count($data['groups']) == 0)
                                <p class="text-danger">There is no group participation for this activity.</p>
                            @else
                                @foreach ($data['groups'] as $group)
                                    <p class="text-info"><b>{{ $group['groupName'] }}</b></p>

                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <th>Name</th>
                                            <th>IC / Passport No.</th>
                                            <th>Attendance</th>
                                        </thead>

                                        <tbody>
                                            @foreach ($group['members'] as $member)
                                                <tr>
                                                    <td style="width: 35%">
                                                        @if ($member->category == "Newbie")
                                                            <a href="#modal-participant" class="text-warning" data-toggle="modal" data-id="{{ $member->user_id}}">
                                                                <b><mark class="text-warning">{{ $member->full_name }}</mark></b>
                                                            </a>
                                                        @else
                                                            <a href="#modal-participant" data-toggle="modal" data-id="{{ $member->user_id}}">
                                                                {{ $member->full_name }}
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td style="width: 25%">{{ $member->ic_passport }}</td>
                                                    <td style="width: 40%">
                                                        @if ($member->status == "J")
                                                            
                                                            {!! Form::open(['action' => ['ParticipationController@absent', $data['activity']->activity_id, $member->participation_id], 'onsubmit' => 'return confirmMsg("absent", "'.$member->full_name.'");', 'method' => 'POST']) !!}

                                                                {{Form::hidden('_method', 'PUT')}}
                                                                @if (Carbon::today() < Carbon::parse($data['activity']->activity_date))
                                                                    {{ Form::button('<i class="fa fa-thumbs-down"></i><span> Absent</span>', ['class' => 'btn btn-danger pull-left disabled', 'style' => 'margin-right:5px'] )  }}
                                                                @else
                                                                    {{ Form::button('<i class="fa fa-thumbs-down"></i><span> Absent</span>', ['type' => 'submit', 'class' => 'btn btn-danger pull-left', 'style' => 'margin-right:5px'] )  }}   
                                                                @endif
                    
                                                            {!! Form::close() !!}
                                                    
                                                            {!! Form::open(['action' => ['ParticipationController@present', $data['activity']->activity_id, $member->participation_id], 'onsubmit' => 'return confirmMsg("present", "'.$member->full_name.'");', 'method' => 'POST']) !!}

                                                                {{Form::hidden('_method', 'PUT')}}
                                                                @if (Carbon::today() < Carbon::parse($data['activity']->activity_date))
                                                                    {{ Form::button('<i class="fa fa-thumbs-up"></i><span> Present</span>', ['class' => 'btn btn-success pull-left disabled'] )  }}
                                                                @else
                                                                    {{ Form::button('<i class="fa fa-thumbs-up"></i><span> Present</span>', ['type' => 'submit', 'class' => 'btn btn-success pull-left'] )  }}
                                                                @endif
                    
                                                            {!! Form::close() !!}

                                                        @elseif($member->status == "A")
                                                            <span class="text-danger"><i class="fa fa-thumbs-down"></i><span> Absent</span>

                                                        @elseif($member->status == "P")
                                                            <span class="text-success"><i class="fa fa-thumbs-up"></i><span> Present</span>
                                                        @endif
                                                        
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <br>
                                    <hr>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>            
        @endif

        <!-- modal to show activity details -->
        <div class="modal fade" id="modal-activity">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="activity-title"></h4>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tr>
                                <td style="width: 30%"><b>Date</b></td>
                                <td style="width: 70%" id="modal-date"></td>
                            </tr>

                            <tr>
                                <td><b>Start Time</b></td>
                                <td id="modal-start-time"></td>
                            </tr>

                            <tr>
                                <td><b>End Time</b></td>
                                <td id="modal-end-time"></td>
                            </tr>

                            <tr>
                                <td><b>Description</b></td>
                                <td id="modal-description"></td>
                            </tr>

                            <tr>
                                <td><b>Remark</b></td>
                                <td id="modal-remark"></td>
                            </tr>

                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- modal to show participant details -->
        <div class="modal fade" id="modal-participant">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="participant-fullname"></h4>
                    </div>
                    <div class="modal-body">
                        <img id="participant-image" class="profile-user-img img-responsive" alt="User Image" />

                        <br>

                        <table class="table">
                            <tr>
                                <td style="width: 40%"><b>IC / Passport No.</b></td>
                                <td style="width: 60%" id="participant-ic"></td>
                            </tr>

                            <tr>
                                <td><b>Gender</b></td>
                                <td id="participant-gender"></td>
                            </tr>

                            <tr>
                                <td><b>Contact No.</b></td>
                                <td id="participant-contact"></td>
                            </tr>

                            <tr>
                                <td><b>Emergency Contact Person</b></td>
                                <td id="emergency-contact-person"></td>
                            </tr>

                            <tr>
                                <td><b>Emergency Contact No.</b></td>
                                <td id="emergency-contact-no"></td>
                            </tr>

                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        
    </section>
@endsection

@section('js')
    <script type="text/javascript">
        function confirmMsg(type, name) {
            var msg;
            
            if(type == "absent") {
                msg = "Are you sure to record the attendance of " + name + " as ABSENT?";
            }
            else if(type == "present") {
                msg = "Are you sure to record the attendance of " + name + " as PRESENT?";
            }
            else if(type == "cancel") {
                msg = "Are you sure to cancel the participation of " + name + "?";
            }
            
            return confirm(msg);
        }

        $(function () {
            //get the data returend
            var data = JSON.parse('<?php echo json_encode($data); ?>');

            $('#modal-activity').on('show.bs.modal', function (e) {
                $('#activity-title').html(data.activity.activity_title);
                $('#modal-date').html(data.activity.activity_date);
                $('#modal-start-time').html(data.activity.start_time);
                $('#modal-end-time').html(data.activity.end_time);
                $('#modal-description').html(data.activity.description);
                $('#modal-remark').html(data.activity.remark);
            });

            $('#modal-participant').on('show.bs.modal', function (e) {
                var user_id = $(e.relatedTarget).data('id');
                $.ajax({
                    type : 'get',
                    url : '/participation/participant/' + user_id, //Here you will fetch records 
                    success : function(data){
                        $('#participant-fullname').html(data.participant.full_name);//Show fetched data from database
                        $('#participant-image').attr('src', data.participant.image_url);
                        $('#participant-ic').html(data.participant.ic_passport);
                        $('#participant-gender').html(data.participant.gender);
                        $('#participant-contact').html(data.participant.phone_no);
                        $('#emergency-contact-person').html(data.participant.emergency_name + 
                            " (" + data.participant.emergency_relation + ")");
                        $('#emergency-contact-no').html(data.participant.emergency_contact);
                    }
                });
            });
        });
    </script>
@endsection