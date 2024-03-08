@extends('layouts.app')

@section('content')

    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="javascript:void(0)">Logo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto" id="myTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="live-tab" data-bs-toggle="tab" data-bs-target="#live"
                                type="button" role="tab" aria-controls="live" aria-selected="true">
                            Live
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history"
                                type="button" role="tab" aria-controls="history" aria-selected="false">
                            Playback
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ms-auto" style="float:right">
            <!-- Authentication Links -->
            @guest

            @else
                <li class="nav-item">

                    @if(Auth::check())
                        <a class="nav-link" href="{{ route('logout') }}">{{ 'Logout'}}</a>
                    @endif
                </li>
            @endguest
        </ul>
    </nav>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="live" role="tabpanel" aria-labelledby="live-tab">
            <div class="row p-5">
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-header">
                            Drilling Parameter
                        </div>
                        <div class="card-body" style="height: 500px">
                            <canvas id="chart1"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-header">
                            Pressure Parameter
                        </div>
                        <div class="card-body" style="height: 500px">
                            <canvas id="chart2"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-header">
                            Mud Parameter
                        </div>
                        <div class="card-body" style="height: 500px">
                            <canvas id="chart3"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-header">
                            Data
                        </div>
                        <div class="card-body" style="height: 500px">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">TOTAL DEPTH(M)</strong>
                                    <span class="badge bg-primary rounded-pill" id="totalDepth">0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">BIT DEPTH(M)</strong>
                                    <span class="badge bg-primary rounded-pill" id="bitDepth">0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">WOH(KLBF)</strong>
                                    <span class="badge bg-primary rounded-pill" id="woh">0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">WOB(KLBF)</strong>
                                    <span class="badge bg-primary rounded-pill" id="wob">0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">ROPA(m/h)</strong>
                                    <span class="badge bg-primary rounded-pill" id="ropa">0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">RPM(RPM)</strong>
                                    <span class="badge bg-primary rounded-pill" id="rpm">0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">TQ(KLBF.FT)</strong>
                                    <span class="badge bg-primary rounded-pill" id="tq">0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">SPP(PSI)</strong>
                                    <span class="badge bg-primary rounded-pill" id="spp">0</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">TG(%)</strong>
                                    <span class="badge bg-primary rounded-pill" id="tg">0</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">

            <div class="col-12 col-md-4 d-flex flex-column p-5 pb-1">

                <div class="input-group p-2">
                    <div class="input-group-prepend w-30">
                        <span class="input-group-text">Select date</span>
                    </div>
                    <input type="date" id="date" name="date" class="form-control"
                           placeholder="date"
                           aria-describedby="date">
                </div>

                <div class="input-group p-2">
                    <div class="input-group-prepend w-30">
                        <span class="input-group-text">Start time</span>
                    </div>
                    <input type="time" id="startTime" name="startTime" class="form-control"
                           placeholder="start time"
                           aria-describedby="stime">
                </div>

                <div class="input-group p-2">
                    <div class="input-group-prepend w-30">
                        <span class="input-group-text">End time</span>
                    </div>
                    <input type="time" id="endTime" name="endTime" class="form-control"
                           placeholder="end time"
                           aria-describedby="etime">
                </div>

                <div class="p-2">
                    <button type="button" class="btn btn-primary" onclick="submitFilterData()">submit</button>
                </div>
            </div>

            <div class="row p-5">

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header">
                            Drilling Parameter
                        </div>
                        <div class="card-body" style="height: 500px">
                            <canvas id="chart4"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header">
                            Pressure Parameter
                        </div>
                        <div class="card-body" style="height: 500px">
                            <canvas id="chart5"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header">
                            Mud Parameter
                        </div>
                        <div class="card-body" style="height: 500px">
                            <canvas id="chart6"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let intervalId;

        function submitFilterData() {
            let date = $("#date").val();
            let startTime = $("#startTime").val();
            let endTime = $("#endTime").val();
            console.log('date', date);
            console.log('startTime', startTime);
            console.log('endTime', endTime);
            if (!date || !startTime || !endTime) {
                return;
            }

            if (endTime < startTime) {
                return;
            }

            let startDate = date + "*" + startTime + ":00";
            let endDate = date + "*" + endTime + ":00";

            getHistoryData(startDate, endDate);
        }

        $(document).ready(function () {
            getLiveData();
            getLatestData();

            new Promise(() => {
                intervalId = setInterval(() => {
                    getLiveData();
                    getLatestData();
                }, 10000);
            });
        });

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            let targetTab = $(e.target).attr('aria-controls')
            if (targetTab === 'history') {
                getHistoryData(null, null);
                clearInterval(intervalId);
            }
        });

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            let targetTab = $(e.target).attr('aria-controls')
            if (targetTab === 'live') {
                getLiveData();
                getLatestData();

                new Promise(() => {
                    intervalId = setInterval(() => {
                        getLiveData();
                        getLatestData();
                    }, 10000);
                });
            }
        });

        async function getLiveData() {
            let response = await callApi("live/data", "GET", null);
            response = JSON.parse(response);
            let data = preparingData(response);

            chartDisplay("chart1", data.labels, data.drilling, data.drillingOptions);
            chartDisplay("chart2", data.labels, data.pressure, data.pressureOptions);
            chartDisplay("chart3", data.labels, data.mud, data.mudOptions);
        }

        async function getHistoryData(startDate, endDate) {
            let response;
            if (startDate) {
                response = await callApi("history/data/" + startDate + "/" + endDate, "GET");
            } else {
                response = await callApi("history/data", "GET");
            }

            response = JSON.parse(response);
            let data = preparingData(response);

            chartDisplay("chart4", data.labels, data.drilling, data.drillingOptions);
            chartDisplay("chart5", data.labels, data.pressure, data.pressureOptions);
            chartDisplay("chart6", data.labels, data.mud, data.mudOptions);
        }

        function preparingData(apiResponse) {

            let drillingParameters = apiResponse.drillingParameters;
            let drillingData = drillingParameters.dataSet;
            let drillingColors = drillingParameters.colors;
            let drillingOptions = drillingParameters.options;

            let drillingParameterDatasets = [];
            for (let key in drillingData) {
                let dataset = {
                    label: key,
                    data: drillingData[key],
                    borderWidth: 1,
                    borderColor: drillingColors[key]
                };
                drillingParameterDatasets.push(dataset);
            }

            let mudParameters = apiResponse.mudParameters;
            let mudData = mudParameters.dataSet;
            let mudColors = mudParameters.colors;
            let mudOptions = mudParameters.options;

            let mudParametersDatasets = [];
            for (let key in mudData) {
                if (key === "colors")
                    continue;
                let dataset = {
                    label: key,
                    data: mudData[key],
                    borderWidth: 1,
                    borderColor: mudColors[key],
                };
                mudParametersDatasets.push(dataset);
            }

            let pressureParameters = apiResponse.pressureParameters;
            let pressureData = pressureParameters.dataSet;
            let pressureColors = pressureParameters.colors;
            let pressureOptions = pressureParameters.options;

            let pressureParametersDatasets = [];
            for (let key in pressureData) {
                if (key === "colors")
                    continue;
                let dataset = {
                    label: key,
                    data: pressureData[key],
                    borderWidth: 1,
                    borderColor: pressureColors[key]
                };
                pressureParametersDatasets.push(dataset);
            }

            return {
                "drilling": drillingParameterDatasets,
                "drillingOptions": drillingOptions,
                "mud": mudParametersDatasets,
                "mudOptions": mudOptions,
                "pressure": pressureParametersDatasets,
                "pressureOptions": pressureOptions,
                "labels": apiResponse.tags
            };
        }

        function chartDisplay(chartId, labels, datasets, options) {
            chartDestroy(chartId);
            const ctx = document.getElementById(chartId).getContext('2d');
            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            min: options.min,
                            // max: options.max
                        },
                        y: {
                            // type: 'time',
                            // ticks: {
                            //     // forces step size to be 50 units
                            //     stepSize: 5
                            // },
                            // time: {
                            //     unit: 'minute',
                            //     stepSize: 5,
                            //     displayFormats: {
                            //         minute: 'HH:mm'
                            //     },
                            //     tooltipFormat: 'HH:mm'
                            // }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                    },
                    hover: {
                        mode: 'index',
                        intersec: false
                    },
                    elements: {
                        point: {
                            radius: 1
                        }
                    },
                    transitions: {
                        show: {
                            animations: {
                                x: {
                                    from: 0
                                },
                                y: {
                                    from: 0
                                }
                            }
                        },
                        hide: {
                            animations: {
                                x: {
                                    to: 0
                                },
                                y: {
                                    to: 0
                                }
                            }
                        }
                    }
                }
            });
        }

        function chartDestroy(chartId) {
            let chart = Chart.getChart(chartId);
            if (chart) {
                chart.destroy();
            }
        }

        async function getLatestData() {
            let response = await callApi("live/latest-data", "GET", null);
            if (response) {
                response = JSON.parse(response);

                // $("#totalDepth").text(response.DEPTVD);
                // $("#bitDepth").text(response.DEPBITTVD);
                $("#woh").text(response.HKLD);
                $("#wob").text(response.WOB);
                // $("#ropa").text(response.ROPA);
                $("#rpm").text(response.SURFRPM);
                $("#tq").text(response.TORQ);
                $("#spp").text(response.SPP);
                $("#tg").text(response.TGAS);
            }
        }

        async function callApi(url, method) {
            let result;
            await $.ajax({
                url: url,
                method: method,
                success: function (response) {
                    result = response;
                }
            });
            return result;
        }
    </script>
@endsection
