@extends('layouts.app')

@section('content')
    <div class="tab">
        <div class="tab-heading">
            <span class="active">Live Charts</span>
            <span>Historical Chart</span>
        </div>

        <div class="tab-content">
            <div id="live_charts">
                <h3 style="margin: 50px 20px">Live Charts</h3>
                <div class="container-fluid h-100">
                    <div class="row justify-content-center h-100">
                        <div class="col-4 hidden-md-down">
                            <canvas id="line-chart1" class="chart1" style="width:100%;max-width:600px"></canvas>
                        </div>
                        <div class="col-4 hidden-md-down">
                            <canvas id="line-chart2" class="chart2" style="width:100%;max-width:600px"></canvas>
                        </div>
                        <div class="col-4 hidden-md-down">
                            <canvas id="line-chart3" class="chart3" style="width:100%;max-width:600px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div id="historical_charts">
                <div style="width:100%;margin-top:17px;display:flex;height:45px;">
                    <div id="filters" class="filters" style="width: 60%;margin: auto">
                        <form id="form">
                            <div>
                                <label for="filter-date">From Date &nbsp</label>
                                <input type="text" class="filter-from-date" name="filter-date" id="filter-date"/>
                            </div>

                            <div style="margin-left: 35px">
                                <label for="filter-date">To Date &nbsp</label>
                                <input type="text" class="filter-to-date" name="filter-date" id="filter-date"/>
                            </div>

                            <div style="margin-left: 35px">
                                <button id="submit" class="btn" style="background-color: #810d0d;height:38px;
                                width: 90px;border-radius: 13px;color: white;" onclick="get_history(event)">Filter</button>
                            </div>
                        </form>
                    </div>

                </div>
                <h3 style="margin: 50px 20px">Historical Chart</h3>
                <div class="container-fluid h-100">
                    <div class="row justify-content-center h-100">
                        <div class="col-4 hidden-md-down">
                            <canvas id="line-chart4" class="chart1" style="width:100%;max-width:600px"></canvas>
                        </div>
                        <div class="col-4 hidden-md-down">
                            <canvas id="line-chart5" class="chart2" style="width:100%;max-width:600px"></canvas>
                        </div>
                        <div class="col-4 hidden-md-down">
                            <canvas id="line-chart6" class="chart3" style="width:100%;max-width:600px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{--    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>--}}
    <script>
        var status;

        $(document).ready(function () {

            $(".tab-content div").eq(0).show();

            $('#live_charts').show();
            $('#historical_charts').hide();
            $('#filters').hide();

            status = "live";
            var url = 'display_data_live/';
            // myInterval = setInterval(all, 5000, status, url);
            // var myInterval = setInterval(all(status, url), 2000);
            all(status, url);

            $(".tab-heading span").click(function () {
                $(this)
                    .addClass("active")
                    .css({opacity: 1})
                    .siblings()
                    .removeClass("active")
                    .css({opacity: 0.3})
                var index = $(this).index();
                if (index === 0) {

                    $('#live_charts').show();
                    $('#historical_charts').hide();
                    $('#filters').hide();
                    status = "live";
                    url = 'display_data_live/';
                    // myInterval = setInterval(all, 5000, status, url);
                    // var myInterval = setInterval(all(status, url), 2000);
                    all(status, url);
                } else if (index === 1) {

                    $('#live_charts').hide();
                    $('#historical_charts').show();
                    $('#filters').show();
                    status = "history";
                    url = 'display_data_history/';
                    // clearInterval(myInterval);
                    all(status, url);
                }
            })
        })

        function get_history(event) {
            event.preventDefault();
            status = "history";
            var datetime_start = $(".filter-from-date").val();
            var datetime_end = $(".filter-to-date").val();

            datetime_start = datetime_start.replace("/", '-');
            datetime_start = datetime_start.replace("/", '-');
            datetime_start = datetime_start.replace(" ", '*');
            datetime_end = datetime_end.replace("/", '-');
            datetime_end = datetime_end.replace("/", '-');
            datetime_end = datetime_end.replace(" ", '*');

            var url = 'display_data_history/' + datetime_start + '/' + datetime_end;
            console.log(status);
            console.log(url);
            all(status, url);
        }

        function all(status, url) {
            console.log(url);
            $.ajax({
                url: url,
                method: "get",
                success: function (response) {
                    response = JSON.parse(response)
                    console.log(response)
                    let drillingParameters = response.drillingParameters;
                    let pressureParameters = response.pressureParameters;
                    let mudParameters = response.mudParameters;

                    var labels = response.tags;

                    var drillingParameterDatasets = [];
                    for (var key in drillingParameters) {
                        var dataset = {
                            label: key,
                            data: drillingParameters[key],
                            fill: false,
                            borderColor: getRandomColor(),
                            tension: 0.4
                        };
                        drillingParameterDatasets.push(dataset);
                    }

                    var mudParametersDatasets = [];
                    for (var key in mudParameters) {
                        var dataset = {
                            label: key,
                            data: mudParameters[key],
                            fill: false,
                            borderColor: getRandomColor(),
                            tension: 0.4
                        };
                        mudParametersDatasets.push(dataset);
                    }

                    var pressureParametersDatasets = [];
                    for (var key in pressureParameters) {
                        var dataset = {
                            label: key,
                            data: pressureParameters[key],
                            fill: false,
                            borderColor: getRandomColor(),
                            tension: 0.4
                        };
                        pressureParametersDatasets.push(dataset);
                    }

                    if (status === "history") {
                        chartDisplay("line-chart4", 'Drilling Parameter', labels, drillingParameterDatasets)
                        chartDisplay("line-chart5", 'Pressure Parameter', labels, pressureParametersDatasets)
                        chartDisplay("line-chart6", 'Mud Parameters', labels, mudParametersDatasets)
                    } else {
                        chartDisplay("line-chart1", 'Drilling Parameter', labels, drillingParameterDatasets)
                        chartDisplay("line-chart2", 'Pressure Parameter', labels, pressureParametersDatasets)
                        chartDisplay("line-chart3", 'Mud Parameters', labels, mudParametersDatasets)
                    }
                }
            })
        }

        function chartDisplay(chartId, chartTitle, labels, datasets) {
            chartDestroy(chartId);
            var ctx = document.getElementById(chartId).getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: chartTitle
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    }
                }
            });
            return chart;
        }

        function chartDestroy(chartId) {
            let chart = Chart.getChart(chartId);
            if (chart) {
                chart.destroy();
            }
        }

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
@endsection
