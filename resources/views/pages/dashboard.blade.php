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

        No activity

        </section>
    <!-- /.content -->
    @endsection
@endif
