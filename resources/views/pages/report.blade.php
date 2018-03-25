@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Report
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Report</li>
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

        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-user-secret"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">Staffs</span>
                <span class="info-box-number" id="staff_num"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">Active Volunteers</span>
                <span class="info-box-number" id="active_volunteer_num"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-user-plus"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">New Volunteers</span>
                <span class="info-box-number" id="new_volunteer_num"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-tasks"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">Ongoing Activities</span>
                <span class="info-box-number" id="ongoing_activity_num"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <br>

        <!-- top 10 volunteers -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top 10 Volunteers</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            {{--  <div id="barChartVolunteer" style="height: 300px;"></div>  --}}
                            <canvas id="barChartVolunteer" style="height:350px"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body-->
                </div>
                <!-- /.box -->
            </div>
        </div>

        <br>

        <!-- survey -->
        <div class="row">
            <!-- occupation -->
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Volunteers' Occupation</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="chart">
                                    <canvas id="pieChartOccupation" style="height:250px"></canvas>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <ul class="chart-legend clearfix">
                                    <li><i class="fa fa-circle" style="color: #ff0000"></i> Arts and Entertainment</li>
                                    <li><i class="fa fa-circle" style="color: #ff8c00"></i> Business</li>
                                    <li><i class="fa fa-circle" style="color: #8b0000"></i> Engineering</li>
                                    <li><i class="fa fa-circle" style="color: #8a2be2"></i> Finance</li>
                                    <li><i class="fa fa-circle" style="color: #32dc32"></i> Healthcare and Medicine</li>
                                    <li><i class="fa fa-circle" style="color: #00ffff"></i> Industrial and Manufacturing</li>
                                    <li><i class="fa fa-circle" style="color: #008080"></i> Information Technology</li>
                                    <li><i class="fa fa-circle" style="color: #1e90ff"></i> Law Enforcement and Armed Forces</li>
                                    <li><i class="fa fa-circle" style="color: #f08080"></i> Service</li>
                                    <li><i class="fa fa-circle" style="color: #0000ff"></i> Student</li>
                                    <li><i class="fa fa-circle" style="color: #808000"></i> Unemployed</li>
                                    <li><i class="fa fa-circle" style="color: #c0c0c0"></i> Other</li>
                                </ul>
                            </div>
                        </div>
                        
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <!-- medium -->
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">How volunteers get to know about KSK</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="chart">
                                    <canvas id="pieChartMedium" style="height:250px"></canvas>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <ul class="chart-legend clearfix">
                                    <li><i class="fa fa-circle" style="color: #ff0000"></i> Via friend or family members</li>
                                    <li><i class="fa fa-circle" style="color: #ff8c00"></i> 	
                                        Via my company's CSR programme</li>
                                    <li><i class="fa fa-circle" style="color: #8a2be2"></i> Via printed media / news / radio / TV Programmes</li>
                                    <li><i class="fa fa-circle" style="color: #32dc32"></i> Via social media platform</li>
                                    <li><i class="fa fa-circle" style="color: #00ffff"></i> Via googling or online search</li>
                                    <li><i class="fa fa-circle" style="color: #1e90ff"></i> Via another soup kitchen</li>
                                    <li><i class="fa fa-circle" style="color: #c0c0c0"></i> Other</li>
                                </ul>
                            </div>
                        </div>
                        
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
        
    </section>
@endsection

@section('js')
    <script type="text/javascript">

        $(function () {
            //get the data returend
            var data = JSON.parse('<?php echo json_encode($data); ?>');

            //info boxes
            $('#staff_num').html(data.staffNum);
            $('#active_volunteer_num').html(data.activeVolunteerNum);
            $('#new_volunteer_num').html(data.newVolunteerNum);
            $('#ongoing_activity_num').html(data.ongoingActivityNum);

            //-------------
            //- PIE CHART OCCUPATION-
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvasOccupation = $('#pieChartOccupation').get(0).getContext('2d');
            var pieChartOccupation       = new Chart(pieChartCanvasOccupation);
            var PieDataOccupation        = data.occupations;
            var pieOptionsOccupation     = {
                //Boolean - Whether we should show a stroke on each segment
                segmentShowStroke    : true,
                //String - The colour of each segment stroke
                segmentStrokeColor   : '#fff',
                //Number - The width of each segment stroke
                segmentStrokeWidth   : 2,
                //Number - The percentage of the chart that we cut out of the middle
                percentageInnerCutout: 50, // This is 0 for Pie charts
                //Number - Amount of animation steps
                animationSteps       : 100,
                //String - Animation easing effect
                animationEasing      : 'easeOutBounce',
                //Boolean - Whether we animate the rotation of the Doughnut
                animateRotate        : true,
                //Boolean - Whether we animate scaling the Doughnut from the centre
                animateScale         : false,
                //Boolean - whether to make the chart responsive to window resizing
                responsive           : true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio  : true,
                //String - A legend template
                legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
            };
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            pieChartOccupation.Doughnut(PieDataOccupation, pieOptionsOccupation);

            //-------------
            //- PIE CHART MEDIUM-
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvasMedium = $('#pieChartMedium').get(0).getContext('2d');
            var pieChartMedium       = new Chart(pieChartCanvasMedium);
            var PieDataMedium        = data.mediums;
            var pieOptionsMedium     = {
                //Boolean - Whether we should show a stroke on each segment
                segmentShowStroke    : true,
                //String - The colour of each segment stroke
                segmentStrokeColor   : '#fff',
                //Number - The width of each segment stroke
                segmentStrokeWidth   : 2,
                //Number - The percentage of the chart that we cut out of the middle
                percentageInnerCutout: 50, // This is 0 for Pie charts
                //Number - Amount of animation steps
                animationSteps       : 100,
                //String - Animation easing effect
                animationEasing      : 'easeOutBounce',
                //Boolean - Whether we animate the rotation of the Doughnut
                animateRotate        : true,
                //Boolean - Whether we animate scaling the Doughnut from the centre
                animateScale         : false,
                //Boolean - whether to make the chart responsive to window resizing
                responsive           : true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio  : true,
                //String - A legend template
                legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
            };
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            pieChartMedium.Doughnut(PieDataMedium, pieOptionsMedium);

            /*
            * BAR CHART
            * ---------
            */
            var bar_data = {
                data : data.volunteers,
                color: '#3c8dbc'
            }
            $.plot('#barChartVolunteer2', [bar_data], {
                grid  : {
                    borderWidth: 1,
                    borderColor: '#f3f3f3',
                    tickColor  : '#f3f3f3'
                },
                series: {
                    bars: {
                        show    : true,
                        barWidth: 0.5,
                        align   : 'center'
                    }
                },
                xaxis : {
                    mode      : 'categories',
                    tickLength: 0,
                }
            });
            /* END BAR CHART */

            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas                   = $('#barChartVolunteer').get(0).getContext('2d');
            var barChart                         = new Chart(barChartCanvas);
            var barChartData                     = {
                labels  : data.volunteersName,
                datasets: [
                    {
                        label               : 'Volunteers',
                        fillColor           : '#3c8dbc',
                        strokeColor         : '#3c8dbc',
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : data.volunteersHour
                    }
                ]
            };
            var barChartOptions                  = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero        : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : true,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            //Boolean - If there is a stroke on each bar
            barShowStroke           : true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth          : 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing         : 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing       : 1,
            //String - A legend template
            legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            //Boolean - whether to make the chart responsive
            responsive              : true,
            maintainAspectRatio     : true
            }

            barChartOptions.datasetFill = false
            barChart.Bar(barChartData, barChartOptions)
        });
    </script>
@endsection