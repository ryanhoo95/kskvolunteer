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
                Users
            </h1>
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                @if ($data['type'] == "staff")
                    <li class="active">Staffs</li>
                @else
                    <li class="active">Volunteers</li>
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
            
            <br>

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <!-- box header -->
                        <div class="box-header">
                            <div class="row">
                                <div class="col-xs-6 col-md-2">
                                    <select name="usertype" id="select_usertype" class="form-control pull-left" onchange="changeUserType()">
                                        @if ($data['type'] == "staff")
                                            <option value="staff" selected>Staff</option>
                                            <option value="volunteer">Volunteer</option>
                                        @else
                                            <option value="staff">Staff</option>
                                            <option value="volunteer" selected>Volunteer</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="col-xs-6 col-md-10">
                                    @if ($data['type'] == "staff")
                                        <!-- admin and master admin can add staff -->
                                        @if (AppHelper::currentUserRole() == "Master Admin" || AppHelper::currentUserRole() == "Admin")
                                            <a href="/user/{{ $data['type'] }}/create" class="btn btn-primary pull-right"><i class="fa fa-plus"></i><span> Add Staff</span></a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- box body -->
                        <div class="box-body">
                            @if (count($data['users']) == 0)
                                <p class="text-danger">No user at the moment.</p>
                            @else
                                <table id="datatable" class="table table-bordered table-hover">
                                    <thead>
                                        <th>Profile Name</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone No.</th>

                                        @if ($data['type'] == "staff")
                                            <th>Position</th>
                                        @endif

                                        <th>Status</th>
                                    </thead>

                                    <tbody>
                                        @foreach ($data['users'] as $user)
                                            <tr>
                                                <td><a href="/user/{{ $data['type'] }}/{{ $user->user_id }}/profile">{{ $user->profile_name }}</a></td>
                                                <td><a href="/user/{{ $data['type'] }}/{{ $user->user_id }}/profile">{{ $user->full_name }}</a></td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone_no }}</td>
    
                                                @if ($data['type'] == "staff")
                                                    <td>{{ AppHelper::getUserRole($user->usertype) }}</td>
                                                @endif
    
                                                @if ($user->status == 'A')
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

        <!-- css -->
        <style>
            
        </style>

        <!-- js function -->
        <script type="text/javascript">
            function changeUserType() {
                if (document.getElementById("select_usertype").value == "staff"){
                    window.location = "{{ route('user.index', 'staff') }}";
                }     
                else{
                    window.location = "{{ route('user.index', 'volunteer') }}";
                }    
            }
        </script>
        
    @endsection
@endif