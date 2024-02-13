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
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Drilling Parameter
                        </div>
                        <div class="card-body">
                            <canvas id="chart1"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Pressure Parameter
                        </div>
                        <div class="card-body">
                            <canvas id="chart2"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Mud Parameter
                        </div>
                        <div class="card-body">
                            <canvas id="chart3"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">

            <div class="col-12 col-md-4 d-flex flex-column p-5 pb-1">

                <div class="input-group p-2">
                    <div class="input-group-prepend w-25">
                        <span class="input-group-text">Select date</span>
                    </div>
                    <input type="date" id="date" name="date" class="form-control"
                           placeholder="date"
                           aria-describedby="date">
                </div>

                <div class="input-group p-2">
                    <div class="input-group-prepend w-25">
                        <span class="input-group-text">Start time</span>
                    </div>
                    <input type="time" id="startTime" name="startTime" class="form-control"
                           placeholder="start time"
                           aria-describedby="stime">
                </div>

                <div class="input-group p-2">
                    <div class="input-group-prepend w-25">
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
                    <div class="card">
                        <div class="card-header">
                            Drilling Parameter
                        </div>
                        <div class="card-body">
                            <canvas id="chart4"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Pressure Parameter
                        </div>
                        <div class="card-body">
                            <canvas id="chart5"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Mud Parameter
                        </div>
                        <div class="card-body">
                            <canvas id="chart6"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
        });

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            let targetTab = $(e.target).attr('aria-controls')
            if (targetTab === 'history') {
                getHistoryData(null, null);
            }
        });

        async function getLiveData() {
            let response = await callApi("live/data", "GET", null);
            response = JSON.parse(response);
            let data = preparingData(response);

            chartDisplay("chart1", data.labels, data.drilling);
            chartDisplay("chart2", data.labels, data.pressure);
            chartDisplay("chart3", data.labels, data.mud);

            // await new Promise(() => {
            //     setInterval(() => {
            //         getLiveData();
            //     }, 60000);
            // });
        }

        async function getHistoryData(startDate, endDate) {
            let response;
            if (startDate) {
                response = await callApi("history/data/" + startDate + "/" + endDate, "GET");
            } else {
                response = await callApi("history/data", "GET");
            }

            console.log('getHistoryData response', response);
            response = JSON.parse(response);
            let data = preparingData(response);

            chartDisplay("chart4", data.labels, data.drilling);
            chartDisplay("chart5", data.labels, data.pressure);
            chartDisplay("chart6", data.labels, data.mud);
        }

        function chartDisplay(chartId, labels, datasets) {
            chartDestroy(chartId);
            const ctx = document.getElementById(chartId).getContext('2d');
            return new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    indexAxis: 'y',
                    scales: {
                    },
                    plugins:{
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    },
                    elements: {
                        point:{
                            radius: 0
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

        function preparingData(apiResponse) {
            let drillingParameters = apiResponse.drillingParameters;
            let pressureParameters = apiResponse.pressureParameters;
            let mudParameters = apiResponse.mudParameters;

            let drillingParameterDatasets = [];
            for (let key in drillingParameters) {
                if (key === "colors")
                    continue;
                let dataset = {
                    label: key,
                    data: drillingParameters[key],
                    borderWidth: 1,
                    borderColor: drillingParameters.colors[key]
                };
                drillingParameterDatasets.push(dataset);
            }

            let mudParametersDatasets = [];
            for (let key in mudParameters) {
                if (key === "colors")
                    continue;
                let dataset = {
                    label: key,
                    data: mudParameters[key],
                    borderWidth: 1,
                    borderColor: mudParameters.colors[key],
                };
                mudParametersDatasets.push(dataset);
            }

            let pressureParametersDatasets = [];
            for (let key in pressureParameters) {
                if (key === "colors")
                    continue;
                let dataset = {
                    label: key,
                    data: pressureParameters[key],
                    borderWidth: 1,
                    borderColor: pressureParameters.colors[key]
                };
                pressureParametersDatasets.push(dataset);
            }

            return {
                "drilling": drillingParameterDatasets,
                "mud": mudParametersDatasets,
                "pressure": pressureParametersDatasets,
                "labels": apiResponse.tags
            };
        }
    </script>
@endsection
