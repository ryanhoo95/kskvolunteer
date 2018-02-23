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
            My Profile
        </h1>
        {{--  <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>  --}}
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            <img src="/storage/profile_image/{{ Auth::user()->profile_image }}" class="profile-user-img img-responsive" alt="User Image" />

            <br/>

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="box">
                        <!-- header -->
                        <div class="box-header text-center">
                            <h3 class="box-title"> <b>{{ Auth::user()->profile_name }}</b></h3>
                        </div>

                        <!-- body -->
                        <div class="box-body no-padding">
                            <table class="table">
                                <tr>
                                    <td style="width: 30%"><b>Full Name</b></td>
                                    <td style="width: 70%">{{ Auth::user()->full_name }}</td>
                                </tr>

                                <tr>
                                    <td><b>IC / Passport No.</b></td>
                                    <td>{{ Auth::user()->ic_passport }}</td>
                                </tr>

                                <tr>
                                    <td><b>Gender</b></td>
                                
                                    @if (Auth::user()->gender  == 'M')
                                        <td>Male</td>
                                    @else
                                        <td>Female</td>
                                    @endif
                        
                                </tr>

                                <tr>
                                    <td><b>Date of Birth</b></td>
                                    <td>{{ Carbon::parse(Auth::user()->date_of_birth)->format('d M Y') }}</td>
                                </tr>

                                <tr>
                                    <td><b>Date of Joining</b></td>
                                    <td>{{ Carbon::parse(Auth::user()->created_at)->format('d M Y') }}</td>
                                </tr>

                                <tr>
                                    <td><b>Contact No.</b></td>
                                    <td>{{ Auth::user()->phone_no }}</td>
                                </tr>

                                <tr>
                                    <td><b>Address</b></td>
                                    <td>{{ Auth::user()->address }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- footer -->
                        <div class="box-footer">
                            <a href="/reset_password" class="btn btn-default"><i class="fa fa-expeditedssl"></i><span> Reset Password</span></a>
                            <a href="/profile/{{ Auth::user()->user_id }}/edit" class="btn btn-primary pull-right"><i class="fa fa-edit"></i><span> Edit</span></a>
                        </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
    <!-- /.content -->
    @endsection
    
@endif
