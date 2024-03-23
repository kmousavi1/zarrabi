{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--    <title>Chart.js Custom Tooltip Format Example</title>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>--}}
{{--</head>--}}
{{--<body>--}}
{{--<canvas id="myChart" width="400" height="200"></canvas>--}}

{{--<script>--}}
{{--    var ctx = document.getElementById('myChart').getContext('2d');--}}

{{--    var labels = [--}}
{{--        '2024-03-01 10:26:10',--}}
{{--        '2024-03-01 10:25:20',--}}
{{--        '2024-03-01 10:25:10',--}}
{{--        '2024-03-01 10:20:20',--}}
{{--        '2024-03-01 10:20:10',--}}
{{--    ];--}}

{{--    var myChart = new Chart(ctx, {--}}
{{--        type: 'line',--}}
{{--        data: {--}}
{{--            labels: labels,--}}
{{--            datasets: [{--}}
{{--                label: 'Dataset 1',--}}
{{--                data: [2, 5, 3, 19, 12], // Sample data--}}
{{--                borderColor: 'blue',--}}
{{--                borderWidth: 1--}}
{{--            },--}}
{{--                {--}}
{{--                    label: 'Dataset 2',--}}
{{--                    data: [7, 10, 15, 8, 5], // Sample data--}}
{{--                    borderColor: 'red',--}}
{{--                    borderWidth: 1--}}
{{--                }]--}}
{{--        },--}}
{{--        options: {--}}
{{--            indexAxis: 'y', // Display time labels on the y-axis--}}
{{--            scales: {--}}
{{--                y: {--}}
{{--                    type: 'time',--}}
{{--                    time: {--}}
{{--                        unit: 'minute', // Display y-axis with minutes--}}
{{--                        displayFormats: {--}}
{{--                            minute: 'HH:mm'--}}
{{--                        }--}}
{{--                    },--}}
{{--                    ticks: {--}}
{{--                        stepSize: 3 // Set tick steps to 5 minutes--}}
{{--                    },--}}
{{--                    reverse: true--}}
{{--                }--}}
{{--            },--}}
{{--            plugins: {--}}
{{--                tooltip: {--}}
{{--                    callbacks: {--}}
{{--                        label: function(context) {--}}
{{--                            var label = context.dataset.label || '';--}}
{{--                            if (label) {--}}
{{--                                label += ': ';--}}
{{--                            }--}}
{{--                            if (context.parsed.y !== null) {--}}
{{--                                label += moment(context.parsed.y).format('HH:mm');--}}
{{--                            }--}}
{{--                            return label;--}}
{{--                        }--}}
{{--                    }--}}
{{--                }--}}
{{--            }--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}

    <!DOCTYPE html>
<html>
<head>
    <title>Real-time Chart Update Example</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<canvas id="myChart" width="400" height="200"></canvas>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Real-time Data',
                data: [],
                borderColor: 'blue',
                borderWidth: 1,
                fill: false
            }]
        },
        options: {
            animation: {
                duration: 0 // Disable animations for real-time updates
            },
            scales: {
                x: {
                    display: true // Hide x-axis labels for real-time updates
                }
            }
        }
    });

    function updateChart() {
        // Add a new random data point
        var newDataPoint = Math.floor(Math.random() * 100);
        myChart.data.datasets[0].data.push(newDataPoint);

        // Limit the number of data points to display
        var maxDataPoints = 10;
        if (myChart.data.datasets[0].data.length > maxDataPoints) {
            myChart.data.datasets[0].data.shift();
        }

        // Update the chart
        myChart.update();
    }

    // Update the chart every 5 seconds
    setInterval(updateChart, 5000);
</script>
</body>
</html>
