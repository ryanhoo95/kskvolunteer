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
               {{ $data['user']->profile_name }}'s Profile
            </h1>
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                @if ($data['type'] == "staff")
                    <li><a href="/user/staff">Staffs</a></li>
                @else
                    <li><a href="/user/volunteer">Volunteers</a></li>
                @endif
                <li class="active">{{ $data['user']->profile_name }}</li>
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

            <img src="/storage/profile_image/{{ $data['user']->profile_image }}" class="profile-user-img img-responsive" alt="User Image" />

            <br/>

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="box">
                        <!-- header -->
                        <div class="box-header text-center">
                            <h3 class="box-title"> <b>{{ $data['user']->profile_name }}</b></h3>
                        </div>

                        <!-- body -->
                        <div class="box-body no-padding">
                            <table class="table">
                                <tr>
                                    <td style="width: 30%"><b>Full Name</b></td>
                                    <td id="full_name" style="width: 70%">{{ $data['user']->full_name }}</td>
                                </tr>

                                <tr>
                                    <td><b>Email</b></td>
                                    <td>{{ $data['user']->email }}</td>
                                </tr>

                                <tr>
                                    <td><b>IC / Passport No.</b></td>
                                    <td>{{ $data['user']->ic_passport }}</td>
                                </tr>

                                <tr>
                                    <td><b>Gender</b></td>
                                
                                    @if ($data['user']->gender  == 'M')
                                        <td>Male</td>
                                    @else
                                        <td>Female</td>
                                    @endif
                        
                                </tr>

                                <tr>
                                    <td><b>Date of Birth</b></td>
                                    <td>{{ Carbon::parse($data['user']->date_of_birth)->format('d M Y') }}</td>
                                </tr>

                                <tr>
                                    <td><b>Date of Joining</b></td>
                                    <td>{{ Carbon::parse($data['user']->created_at)->format('d M Y') }}</td>
                                </tr>

                                <tr>
                                    <td><b>Position</b></td>
                                    <td>{{ AppHelper::getUserRole($data['user']->usertype) }}</td>
                                </tr>

                                <tr>
                                    <td><b>Contact No.</b></td>
                                    <td>{{ $data['user']->phone_no }}</td>
                                </tr>

                                <tr>
                                    <td><b>Address</b></td>
                                    <td>{{ $data['user']->address }}</td>
                                </tr>

                                <!-- display the volunteer profile if this user is a volunteer -->
                                @if (AppHelper::getUserRole($data['user']->usertype) == "Volunteer" && $data['volunteer_profile'])
                                    <tr>
                                        <td><b>Emergency Contact Person</b></td>
                                        <td>{{ $data['volunteer_profile']->emergency_name }} ({{ $data['volunteer_profile']->emergency_relation }})</td>
                                    </tr>

                                    <tr>
                                        <td><b>Emergency Contact No.</b></td>
                                        <td>{{ $data['volunteer_profile']->emergency_contact }}</td>
                                    </tr>

                                    <tr>
                                        <td><b>Total Volunteer Duration</b></td>
                                        <td>{{ $data['volunteer_profile']->total_volunteer_duration }}</td>
                                    </tr>
                                @endif

                                <tr>
                                    <td><b>Status</b></td>
                                    @if ($data['user']->status == "A")
                                        <td class="text-success"><b>Active</b></td>
                                    @else
                                        <td class="text-danger"><b>Inactive</b></td>
                                    @endif
                                </tr>
                            </table>
                        </div>

                        <!-- only accessible by master admin or admin -->
                        <!-- admin cannot perform any action on master admin profile and other admins-->
                        @if (AppHelper::currentUserRole() != "Staff" && AppHelper::getUserRole($data['user']->usertype) != "Master Admin" && Auth::user()->usertype != $data['user']->usertype)
                            <!-- footer -->
                            <div class="box-footer">
                                <div class="row">
                                    <!-- reset password -->
                                    <div class="col-md-4">
                                        {!! Form::open(['action' => ['UserController@update', $data['type'], $data['user']->user_id, 'reset_password'], 'onsubmit' => 'return confirmMsg("reset_password");', 'method' => 'POST']) !!}

                                            {{Form::hidden('_method', 'PUT')}}
                                            {{ Form::button('<i class="fa fa-expeditedssl"></i><span> Reset Password</span>', ['type' => 'submit', 'class' => 'btn btn-warning'] )  }}

                                        {!! Form::close() !!}
                                    </div>

                                    <div class="col-md-4">
                                        <!-- admin and master admin can promote volunteer to staff -->
                                        @if (AppHelper::getUserRole($data['user']->usertype) == "Volunteer")
                                            {!! Form::open(['action' => ['UserController@update', $data['type'], $data['user']->user_id, 'promote_to_staff'], 'onsubmit' => 'return confirmMsg("promote_to_staff");', 'method' => 'POST']) !!}

                                                {{Form::hidden('_method', 'PUT')}}
                                                {{ Form::button('<i class="fa fa-arrow-circle-up"></i><span> Promote to Staff</span>', ['type' => 'submit', 'class' => 'btn btn-primary'] )  }}

                                            {!! Form::close() !!}

                                        <!-- master admin can promote staff to admin -->
                                        @elseif(AppHelper::getUserRole($data['user']->usertype) == "Staff" && AppHelper::currentUserRole() == "Master Admin")
                                            {!! Form::open(['action' => ['UserController@update', $data['type'], $data['user']->user_id, 'promote_to_admin'], 'onsubmit' => 'return confirmMsg("promote_to_admin");', 'method' => 'POST']) !!}

                                                {{Form::hidden('_method', 'PUT')}}
                                                {{ Form::button('<i class="fa fa-arrow-circle-up"></i><span> Promote to Admin</span>', ['type' => 'submit', 'class' => 'btn btn-primary'] )  }}

                                            {!! Form::close() !!}

                                        <!-- Master admin can demote admin to staff -->
                                        @elseif(AppHelper::getUserRole($data['user']->usertype) == "Admin")
                                            {!! Form::open(['action' => ['UserController@update', $data['type'], $data['user']->user_id, 'demote_to_staff'], 'onsubmit' => 'return confirmMsg("demote_to_staff");', 'method' => 'POST']) !!}

                                                {{Form::hidden('_method', 'PUT')}}
                                                {{ Form::button('<i class="fa fa-arrow-circle-down"></i><span> Demote to Staff</span>', ['type' => 'submit', 'class' => 'btn btn-primary'] )  }}

                                            {!! Form::close() !!}
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        @if ($data['user']->status == "A")
                                            {!! Form::open(['action' => ['UserController@update', $data['type'], $data['user']->user_id, 'deactivate'], 'onsubmit' => 'return confirmMsg("deactivate");', 'method' => 'POST']) !!}

                                                {{Form::hidden('_method', 'PUT')}}
                                                {{ Form::button('<i class="fa fa-thumbs-down"></i><span> Deactivate User</span>', ['type' => 'submit', 'class' => 'btn btn-danger pull-right'] )  }}

                                            {!! Form::close() !!}
                                        @else
                                            {!! Form::open(['action' => ['UserController@update', $data['type'], $data['user']->user_id, 'activate'], 'onsubmit' => 'return confirmMsg("activate");', 'method' => 'POST']) !!}

                                                {{Form::hidden('_method', 'PUT')}}
                                                {{ Form::button('<i class="fa fa-thumbs-up"></i><span> Activate User</span>', ['type' => 'submit', 'class' => 'btn btn-success pull-right'] )  }}

                                            {!! Form::close() !!}
                                        @endif
                                    </div>
                                </div>
                            </div>   
                        @endif
                    </div>
                </div>
            </div>    
        </section>

        <script type="text/javascript">
            function confirmMsg(type) {
                var msg;
                var user = document.getElementById("full_name").innerHTML;
                if(type == "reset_password") {
                    msg = "Are you sure to reset password of " + user + "?";
                }
                else if(type == "deactivate") {
                    msg = "Are you sure to deactivate " + user + " ?";
                }
                else if(type == "activate") {
                    msg = "Are you sure to activate " + user + " ?";
                }
                else if(type == "activate") {
                    msg = "Are you sure to activate " + user + " ?";
                }
                else if(type == "promote_to_staff") {
                    msg = "Are you sure to promote " + user + " as Staff?";
                }
                else if(type == "promote_to_admin") {
                    msg = "Are you sure to promote " + user + " as Admin?";
                }
                else if(type == "demote_to_staff") {
                    msg = "Are you sure to demote " + user + " as Staff?";
                }
                

                return confirm(msg);
            }
        </script>
    @endsection
@endif