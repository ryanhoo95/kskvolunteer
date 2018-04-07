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
                Activities
            </h1>
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Activities</li>
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
                        <!-- box header -->
                        <div class="box-header">
                            <div class="row">
                                <div class="col-xs-6 col-md-2">
                                    @if (AppHelper::currentUserRole() == "Master Admin" || AppHelper::currentUserRole() == "Admin")
                                        <select name="activity_view" id="select_activity_view" class="form-control pull-left" onchange="changeActivityView()">
                                            <option value="activity" selected>Activity</option>
                                            <option value="activity_type">Activity Template</option>
                                        </select>
                                    @endif
                                </div>

                                <div class="col-xs-6 col-md-10">
                                    <a href="/activity/create" class="btn btn-primary pull-right"><i class="fa fa-plus"></i><span> Add Activity</span></a>
                                </div>
                            </div>
                        </div>

                        <!-- box body -->
                        <div class="box-body table-responsive">
                            @if (count($data['activities']) == 0)
                                <p class="text-danger">No activity at the moment.</p>
                            @else
                                <table id="datatable" class="table table-bordered table-hover">
                                    <thead>
                                        <th>Title</th>
                                        <th>For</th>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Slot</th>
                                        <th>Creator</th>
                                    </thead>

                                    <tbody>
                                        @foreach ($data['activities'] as $activity)
                                            <tr>
                                                <td><a href="/activity/{{ $activity->activity_id }}">{{ $activity->activity_title }}</a></td>
                                                
                                                @if ($activity->access  == 'R')
                                                    <td>Regular</td>
                                                @elseif($activity->access  == 'N')
                                                    <td>Newbie</td>
                                                @else
                                                    <td>Regular, Newbie</td>
                                                @endif
                                                
                                                <td>{{ Carbon::parse($activity->activity_date)->format('Y-m-d') }}</td>
                                                <td>{{ Carbon::parse($activity->start_time)->format('g:i A') }}</td>
                                                <td>{{ Carbon::parse($activity->end_time)->format('g:i A') }}</td>
                                                <td>{{ $activity->slot }}</td>
                                                <td><a href="/user/staff/{{ $activity->created_by }}/profile">{{ $activity->creator }}</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- js function -->
        <script type="text/javascript">
            function changeActivityView() {
                if (document.getElementById("select_activity_view").value == "activity"){
                    window.location = "{{ route('activity.index') }}";;
                }     
                else{
                    window.location = "{{ route('activity_type.index') }}";;
                }    
            }
        </script>
        
    @endsection
@endif