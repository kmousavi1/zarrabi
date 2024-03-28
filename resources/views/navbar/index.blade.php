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
            <div class="row pt-3 pb-3">
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mb-0">Drilling Parameter</h5>
                            <div class="dropdown">
                                <i class="btn-secondary fa fa-bars" type="button" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                </i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Show Data</a></li>
                                    <li><a class="dropdown-item" href="#">Download Excel</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body" style="height: 500px">
                            <canvas id="chart1"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mb-0">Pressure Parameter</h5>
                            <div class="dropdown">
                                <i class="btn-secondary fa fa-bars" type="button" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                </i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Show Data</a></li>
                                    <li><a class="dropdown-item" href="#">Download Excel</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body" style="height: 500px">
                            <canvas id="chart2"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mb-0">Mud Parameter</h5>
                            <div class="dropdown">
                                <i class="btn-secondary fa fa-bars" type="button" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                </i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Show Data</a></li>
                                    <li><a class="dropdown-item" href="#">Download Excel</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body" style="height: 500px">
                            <canvas id="chart3"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            Data
                        </div>
                        <div class="card-body" style="height: 500px">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">TOTAL DEPTH(M)</strong>
                                    <h5><span class="badge bg-primary rounded-pill" id="totalDepth">0</span></h5>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">BIT DEPTH(M)</strong>
                                    <h5><span class="badge bg-primary rounded-pill" id="bitDepth">0</span></h5>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">WOH(KLBF)</strong>
                                    <h5><span class="badge bg-primary rounded-pill" id="woh">0</span></h5>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">WOB(KLBF)</strong>
                                    <h5><span class="badge bg-primary rounded-pill" id="wob">0</span></h5>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">ROPA(m/h)</strong>
                                    <h5><span class="badge bg-primary rounded-pill" id="ropa">0</span></h5>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">RPM(RPM)</strong>
                                    <h5><span class="badge bg-primary rounded-pill" id="rpm">0</span></h5>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">TQ(KLBF.FT)</strong>
                                    <h5><span class="badge bg-primary rounded-pill" id="tq">0</span></h5>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">SPP(PSI)</strong>
                                    <h5><span class="badge bg-primary rounded-pill" id="spp">0</span></h5>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong class="list-group-left">TG(%)</strong>
                                    <h5><span class="badge bg-primary rounded-pill" id="tg">0</span></h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                {{--<div class="col-md-3">
                    <div class="card">
                        <div class="card-header">Test</div>
                        <div class="card-body">Test</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">Test</div>
                        <div class="card-body">Test</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">Test</div>
                        <div class="card-body">Test</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">Test</div>
                        <div class="card-body">Test</div>
                    </div>
                </div>--}}
            </div>
        </div>

        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">

            <div class="col-12 col-md-4 d-flex flex-column pt-3 pb-3">

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

            <div class="row">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mb-0">Drilling Parameter</h5>
                            <div class="dropdown">
                                <i class="btn-secondary fa fa-bars" type="button" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                </i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Show Data</a></li>
                                    <li><a class="dropdown-item" href="#">Download Excel</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body" style="height: 500px">
                            <canvas id="chart4"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mb-0">Pressure Parameter</h5>
                            <div class="dropdown">
                                <i class="btn-secondary fa fa-bars" type="button" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                </i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Show Data</a></li>
                                    <li><a class="dropdown-item" href="#">Download Excel</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body" style="height: 500px">
                            <canvas id="chart5"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mb-0">Mud Parameter</h5>
                            <div class="dropdown">
                                <i class="btn-secondary fa fa-bars" type="button" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                </i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Show Data</a></li>
                                    <li><a class="dropdown-item" href="#">Download Excel</a></li>
                                </ul>
                            </div>
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
        let liveLabels = [];
        let liveDrillingData = new Map();
        let livePressureData = new Map();
        let liveMudData = new Map();

        function submitFilterData() {
            let date = $("#date").val();
            let startTime = $("#startTime").val();
            let endTime = $("#endTime").val();

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
                    getRealTimeData();
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
                        console.log('second')
                        getRealTimeData();
                        getLatestData();
                    }, 10000);
                });
            }
        });

        async function getLiveData() {
            let response = await callApi("live/data", "GET", null);
            response = JSON.parse(response);
            let data = preparingData(response, "LIVE");

            chartDisplay("chart1", data.labels, data.drilling, data.drillingOptions);
            chartDisplay("chart2", data.labels, data.pressure, data.pressureOptions);
            chartDisplay("chart3", data.labels, data.mud, data.mudOptions);
        }

        async function getRealTimeData() {
            let response = await callApi("live/real-time", "GET", null);
            console.log('response', response);

            liveLabels.push(response.tags);
            liveDrillingData.forEach((value, key, map) => {
                value.push(response.drilling[key]);
            });
            livePressureData.forEach((value, key, map) => {
                value.push(response.pressure[key]);
            });
            liveMudData.forEach((value, key, map) => {
                value.push(response.mud[key]);
            });

            let chart1Date1 = Math.min(...liveLabels.map(item => new Date(item)));
            let chart1Date2 = Math.max(...liveLabels.map(item => new Date(item)));
            let diffHours = diff_hours(chart1Date1, chart1Date2);

            let chart1 = getChart("chart1");
            if (chart1) {
                const chart1Data = chart1.data;
                if (chart1Data.datasets.length > 0) {
                    chart1Data.labels = liveLabels;
                    for (let index = 0; index < chart1Data.datasets.length; ++index) {
                        let dataset = chart1Data.datasets[index];
                        dataset.data = liveDrillingData.get(dataset.label);
                    }
                    chart1.update();
                }

                if (chart1Data.datasets.length > 0) {
                    if (diffHours > 60) {
                        liveLabels.shift();
                        chart1Data.labels = liveLabels;
                        chart1Data.datasets.forEach(dataset => {
                            let value = liveDrillingData.get(dataset.label);
                            value.shift();
                            liveDrillingData.set(dataset.label, value);
                            dataset.data = value;
                        });
                    }
                    chart1.update();
                }
            }

            let chart2 = getChart("chart2");
            if (chart2) {
                const chart2Data = chart2.data;
                if (chart2Data.datasets.length > 0) {
                    chart2Data.labels = liveLabels;
                    for (let index = 0; index < chart2Data.datasets.length; ++index) {
                        let dataset = chart2Data.datasets[index];
                        dataset.data = livePressureData.get(dataset.label);
                    }
                    chart2.update();
                }

                if (chart2Data.datasets.length > 0) {
                    if (diffHours > 60) {
                        liveLabels.shift();
                        chart2Data.labels = liveLabels;
                        chart2Data.datasets.forEach(dataset => {
                            let value = livePressureData.get(dataset.label);
                            value.shift();
                            livePressureData.set(dataset.label, value);
                            dataset.data = value;
                        });
                    }
                    chart2.update();
                }
            }

            let chart3 = getChart("chart3");
            if (chart3) {
                const chart3Data = chart3.data;
                if (chart3Data.datasets.length > 0) {
                    chart3Data.labels = liveLabels;
                    for (let index = 0; index < chart3Data.datasets.length; ++index) {
                        let dataset = chart3Data.datasets[index];
                        dataset.data = liveMudData.get(dataset.label);
                    }
                    chart3.update();
                }

                if (chart3Data.datasets.length > 0) {
                    if (diffHours > 60) {
                        liveLabels.shift();
                        chart3Data.labels = liveLabels;
                        chart3Data.datasets.forEach(dataset => {
                            let value = liveMudData.get(dataset.label);
                            value.shift();
                            liveMudData.set(dataset.label, value);
                            dataset.data = value;
                        });
                    }
                    chart3.update();
                }
            }
        }

        function diff_hours(dt1, dt2) {
            dt2 = new Date(dt2);
            dt1 = new Date(dt1);
            let diff = (dt2.getTime() - dt1.getTime()) / 1000;
            return diff / 60;
        }

        async function getHistoryData(startDate, endDate) {
            let response;
            if (startDate) {
                response = await callApi("history/data/" + startDate + "/" + endDate, "GET");
            } else {
                response = await callApi("history/data", "GET");
            }

            response = JSON.parse(response);
            console.log('response history', response)
            let data = preparingData(response, "HISTORY");

            chartDisplay("chart4", data.labels, data.drilling, data.drillingOptions);
            chartDisplay("chart5", data.labels, data.pressure, data.pressureOptions);
            chartDisplay("chart6", data.labels, data.mud, data.mudOptions);
        }

        function preparingData(apiResponse, tabKey) {

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
                if (tabKey === "LIVE") {
                    liveDrillingData.set(key, drillingData[key]);
                }
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
                if (tabKey === "LIVE") {
                    liveMudData.set(key, mudData[key]);
                }
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
                if (tabKey === "LIVE") {
                    livePressureData.set(key, pressureData[key]);
                }
            }
            if (tabKey === "LIVE") {
                liveLabels = apiResponse.tags;
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
                            type: 'logarithmic',
                            min: options.min,
                            ticks: {
                                beginAtZero: true
                            }
                        },
                        y: {
                            type: 'time',
                            time: {
                                unit: 'minute',
                                displayFormats: {
                                    minute: 'HH:mm'
                                }
                            },
                            ticks: {
                                stepSize: 3 // Set tick steps to 5 minutes
                            },
                            reverse: true
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
                        intersect: false
                    },
                    elements: {
                        point: {
                            radius: 1
                        }
                    },
                }
            });
        }

        async function addDataToChart(chartId, newLabels, newData) {
            let chart = getChart(chartId);
            if (chart) {
                const data = chart.data;
                if (data.datasets.length > 0) {
                    data.labels.push(newLabels);

                    for (let index = 0; index < data.datasets.length; ++index) {
                        let dataset = data.datasets[index];
                        let value = newData[dataset.label];
                        dataset.data.push(value);
                    }

                    chart.update();
                }
            }
        }

        async function removeData(chartId) {
            let chart = getChart(chartId);
            if (chart) {
                const data = chart.data;
                if (data.datasets.length > 0) {
                    chart.data.labels.splice(0, 1); // remove the label first

                    chart.data.datasets.forEach(dataset => {
                        dataset.data.splice(0, 1);
                    });

                    chart.update();
                }
            }
        }

        function chartDestroy(chartId) {
            let chart = Chart.getChart(chartId);
            if (chart) {
                chart.destroy();
            }
        }

        function getChart(chartId) {
            return Chart.getChart(chartId);
        }

        async function getLatestData() {
            let response = await callApi("live/latest-data", "GET", null);
            if (response) {
                response = JSON.parse(response);

                $("#totalDepth").text(response.DEPTVD);
                $("#bitDepth").text(response.DEPBITTVD);
                $("#woh").text(response.HKLD);
                $("#wob").text(response.WOB);
                $("#ropa").text(response.ROPA);
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
