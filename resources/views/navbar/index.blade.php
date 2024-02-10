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
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                type="button" role="tab" aria-controls="home" aria-selected="true">
                            Live
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="false">
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
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
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
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="history-tab">
            <div class="row">
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
            getLiveDataInterval();
        })

        async function getLiveDataInterval() {
            return await new Promise(() => {
                setInterval(() => {
                    getLiveData();
                }, 60000);
            });
        }

        async function getLiveData() {
            console.log('live data');
            let response = await callApi("live/data");
            console.log('getLiveData response', response);
            response = JSON.parse(response);
            let data = preparingData(response);

            chartDisplay("chart1", data.labels, data.drilling)
            chartDisplay("chart2", data.labels, data.pressure)
            chartDisplay("chart3", data.labels, data.mud)
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
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        }

        function chartDestroy(chartId) {
            let chart = Chart.getChart(chartId);
            if (chart) {
                chart.destroy();
            }
        }

        async function callApi(url) {
            console.log('url', url);
            let result;
            await $.ajax({
                url: url,
                method: "GET",
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
