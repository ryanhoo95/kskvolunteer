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
            {{--  <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol>  --}}
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
                                    @if (Auth::user()->usertype == 1 || Auth::user()->usertype == 2)
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
                        <div class="box-body">
                            @if (count($data['activities']) == 0)
                                <p class="text-danger">No activity at the moment.</p>
                            @else
                                <table id="datatable" class="table table-bordered table-hover">
                                    <thead>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Slot</th>
                                    </thead>

                                    <tbody>
                                        @foreach ($data['activities'] as $activity)
                                            <tr>
                                                <td><a href="/activity/{{ $activity->activity_id }}"</a></td>
                                                <td>{{ Carbon::parse($activity->activity_date)->format('d M Y') }}</td>
                                                <td>{{ Carbon::parse($activity->start_time)->format('g:i A') }}</td>
                                                <td>{{ Carbon::parse($activity->end_time)->format('g:i A') }}</td>
                                                <td>{{ $activity->slot }}</td>
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