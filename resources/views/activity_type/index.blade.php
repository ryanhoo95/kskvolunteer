@if (Auth::guest())
    <script type="text/javascript">
        window.location = "{{ route('login') }}";
    </script>
@else
    <!-- do not allow staff to come this page -->
    @if (Auth::user()->usertype == 3)
        <script type="text/javascript">
            window.location = "{{ route('activity.index') }}";
        </script>
    @else
        @extends('layouts.app')
        
        @section('content')
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Activity Templates
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
                                        <select name="activity_view" id="select_activity_view" class="form-control pull-left" onchange="changeActivityView()">
                                            <option value="activity">Activity</option>
                                            <option value="activity_type" selected>Activity Template</option>
                                        </select>
                                    </div>

                                    <div class="col-xs-6 col-md-10">
                                        <a href="/activity_type/create" class="btn btn-primary pull-right"><i class="fa fa-plus"></i><span> Add Template</span></a>
                                    </div>
                                </div>
                            </div>

                            <!-- box body -->
                            <div class="box-body">
                                @if (count($data['activity_types']) == 0)
                                    <p class="text-danger">No template at the moment.</p>
                                @else
                                    <table id="datatable" class="table table-bordered table-hover">
                                        <thead>
                                            <th>Title</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Status</th>
                                        </thead>

                                        <tbody>
                                            @foreach ($data['activity_types'] as $activity_type)
                                                <tr>
                                                    <td><a href="/activity_type/{{ $activity_type->activity_type_id }}">{{ $activity_type->activity_title }}</a></td>
                                                    <td>{{ Carbon::parse($activity_type->start_time)->format('h:i A') }}</td>
                                                    <td>{{ Carbon::parse($activity_type->end_time)->format('h:i A') }}</td>
                                                    
                                                    @if ($activity_type->status == "A")
                                                        <td class="text-success"><b>Active</b></td>
                                                    @else
                                                        <td class="text-danger"><b>Inactive</b></td>
                                                    @endif
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
@endif