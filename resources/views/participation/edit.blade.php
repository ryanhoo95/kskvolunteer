@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit VIP
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="/participation?date={{ $data['activity']->activity_date }}">Participation</a></li>
            <li><a href="/participation/{{ $data['activity']->activity_id }}">{{ $data['activity']->activity_title }}</a></li>
            <li class="active">Edit VIP</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <br>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    
                    {!! Form::open(['action' => ['ParticipationController@updateVIP', $data['activity']->activity_id, $data['participation']->participation_id], 'method' => 'POST']) !!}

                        <!-- box body -->
                        <div class="box-body">

                            <!-- name -->
                            <div class="form-group has-feedback {{ $errors->has('name') ? ' has-error' : '' }}">
                                {{Form::label('name', 'Name')}}
                                {{Form::text('name', $data['participation']->participant_name, ['class' => 'form-control', 'placeholder' => 'Enter VIP name', 'maxLength' => 100])}}

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        *{{ $errors->first('name') }}
                                    </span>
                                @endif
                            </div>

                            <!-- remark -->
                            <div class="form-group has-feedback {{ $errors->has('remark') ? ' has-error' : '' }}">
                                {{Form::label('remark', 'Remark')}}
                                {{Form::textarea('remark', $data['participation']->participant_remark, ['class' => 'form-control', 'placeholder' => 'Enter remark (Optional)', 'maxLength' => 500, 'rows' => 3, 'id' => 'remark'])}}

                                @if ($errors->has('remark'))
                                    <span class="help-block">
                                        *{{ $errors->first('remark') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- box footer -->
                        <div class="box-footer">
                            <a href="/participation/{{ $data['activity']->activity_id }}" class="btn btn-warning pull-left">Cancel</a>
                            {{Form::hidden('_method', 'PUT')}}
                            {{Form::submit('Submit', ['class' => 'btn btn-primary pull-right'])}}
                        </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(function () {
            CKEDITOR.replace('remark');
        })
    </script>
@endsection