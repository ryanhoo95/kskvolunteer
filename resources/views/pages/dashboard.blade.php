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
            Today's Activities
        </h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Home</li>
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

            <br>

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                

                        <!-- box body -->
                        <div class="box-body table-responsive">
                            @if ($data['activities'])
                                @if (count($data['activities']) == 0)
                                    <p class="text-danger">No activity.</p>
                                @else
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <th>Title</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Participants</th>
                                            <th>Attendance</th>
                                        </thead>

                                        <tbody>
                                            @foreach ($data['activities'] as $activity)
                                                <tr>
                                                    <td><a href="#modal-activity" data-toggle="modal" data-id="{{ $activity->activity_id}}">{{ $activity->activity_title }}</a></td>
                                                    <td>{{ Carbon::parse($activity->start_time)->format('g:i A') }}</td>
                                                    <td>{{ Carbon::parse($activity->end_time)->format('g:i A') }}</td>
                                                    @if ($activity->participation_status == "Full")
                                                        <td class="text-danger">
                                                            <a class="text-danger" href="/participation/{{ $activity->activity_id }}">
                                                                <b>{{ $activity->participation_num }} / {{ $activity->slot }}</b>
                                                            </a>
                                                        </td>
                                                    @else
                                                        <td class="text-success">
                                                            <a class="text-success" href="/participation/{{ $activity->activity_id }}">
                                                                <b>{{ $activity->participation_num }} / {{ $activity->slot }}</b>
                                                            </a>
                                                        </td>
                                                    @endif

                                                    <td class="text-primary">
                                                        <a class="text-primary" href="/participation/{{ $activity->activity_id }}">
                                                            <b>{{ $activity->attendance_num }} / {{ $activity->participation_num }}</b>
                                                        </a>
                                                    </td>
                                                    
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-activity">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
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
        
        </section>
    <!-- /.content -->
    @endsection

    @section('js')
        <script type="text/javascript">
            $(function () {
                //get the data returend
                var data = JSON.parse('<?php echo json_encode($data); ?>');

                $('#modal-activity').on('show.bs.modal', function (e) {
                    var activity_id = $(e.relatedTarget).data('id');
                    
                    for(var i = 0; i < data.activities.length; i++) {
                        if(data.activities[i].activity_id == activity_id) {
                            $('.modal-title').html(data.activities[i].activity_title);
                            $('#modal-date').html(data.activities[i].activity_date);
                            $('#modal-start-time').html(data.activities[i].start_time);
                            $('#modal-end-time').html(data.activities[i].end_time);
                            $('#modal-description').html(data.activities[i].description);
                            $('#modal-remark').html(data.activities[i].remark);
                            break;
                        }
                    }
                });
            });
        </script>
    @endsection
@endif
