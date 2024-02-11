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
                            History
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
            <div class="row p-5">
                <div class="mb-4 col-12">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><label for="datetime">Select Date</label></span>
                        </div>
                        <input type="datetime-local" id="datetime" name="start_at" class="form-control"
                               placeholder="date"
                               aria-describedby="datetime">
                    </div>
                </div>
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
        $(document).ready(function () {
            getLiveData();
        });

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            let targetTab = $(e.target).attr('aria-controls')
            console.log('target', targetTab)
            if (targetTab === 'history') {
                getHistoryData("", "", "");
            }
        });

        async function getLiveData() {
            console.log('live data');
            let response = await callApi("live/data", "GET");
            console.log('getLiveData response', response);
            response = JSON.parse(response);
            let data = preparingData(response);

            chartDisplay("chart1", data.labels, data.drilling);
            chartDisplay("chart2", data.labels, data.pressure);
            chartDisplay("chart3", data.labels, data.mud);

            await new Promise(() => {
                setInterval(() => {
                    getLiveData();
                }, 60000);
            });
        }

        async function getHistoryData(date, startTime, endTime) {
            console.log('history data');
            let response = await callApi("history/data", "GET");
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
                        x: {
                            beginAtZero: true
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
            console.log('url', url);
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