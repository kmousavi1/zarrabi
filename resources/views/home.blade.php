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
                <div style="display: flex">
                    <canvas id="line-chart1" class="chart chart1" style="width:100%;max-width:600px"></canvas>
                    <canvas id="line-chart2" class="chart chart2" style="width:100%;max-width:600px"></canvas>
                    <canvas id="line-chart3" class="chart chart3" style="width:100%;max-width:600px"></canvas>
                </div>
            </div>

            <div id="historical_charts">
                <h3 style="margin: 50px 20px">Historical Chart</h3>
                <div style="display: flex">
                    <canvas id="line-chart4" class="chart chart4"></canvas>
                    <canvas id="line-chart5" class="chart chart5"></canvas>
                    <canvas id="line-chart6" class="chart chart6"></canvas>
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
            status = "live";
            all(status);
            $(".tab-heading span").click(function () {
                $(this).addClass("active").css({opacity: 1}).siblings().removeClass("active").css({opacity: 0.3})
                var index = $(this).index();
                if (index == 0) {
                    $('#live_charts').show();
                    $('#historical_charts').hide();
                    status = "live";
                    all(status);
                } else if (index == 1) {
                    $('#live_charts').hide();
                    $('#historical_charts').show();
                    status = "history";
                    all(status);
                }
            })
        })

        // setInterval(all, 5000);
        function all(status) {
            console.log(status);

            var datetime_start = '2024-01-11*00:00:00';
            var datetime_end = '2024-01-14*00:00:00';
            var url_live = 'display_data_live/';
            var url_history = 'display_data_history/' + datetime_start + '/' + datetime_end;
            var current_url;
            if (status == "history") {
                current_url = url_history;
            } else {
                current_url = url_live;
            }

            $.ajax({
                url: current_url,
                method: "get",
                success: function (response) {
                    response = JSON.parse(response)
                    console.log(response)
                    let drillingParameters = response.drillingParameters;
                    let pressureParameters = response.pressureParameters;
                    let mudParameters = response.mudParameters;

                    var labels = response.labels;

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

                    if (status == "history") {
                        chartDisplay("line-chart4",'Drilling Parameter',labels,drillingParameterDatasets)
                        chartDisplay("line-chart5",'Pressure Parameter',labels,pressureParametersDatasets)
                        chartDisplay("line-chart6",'Mud Parameters',labels,mudParametersDatasets)
                    } else {
                        chartDisplay("line-chart1",'Drilling Parameter',labels,drillingParameterDatasets)
                        chartDisplay("line-chart2",'Pressure Parameter',labels,pressureParametersDatasets)
                        chartDisplay("line-chart3",'Mud Parameters',labels,mudParametersDatasets)
                    }
                }
            })
        }

        function chartDisplay(chartId, chartTitle, labels, datasets) {
            var ctx = document.getElementById(chartId);
            new Chart(ctx, {
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
