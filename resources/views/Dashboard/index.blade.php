@extends('Partials.main')

@section('content')
    <div id="alert-container"></div>

    <div class="row">
        <div class="col-lg-3 col-sm-12">
            <div class="card">
                <div class="stat-widget-one card-body">
                    <div class="stat-icon d-inline-block">
                        <i class="ti-pulse text-success border-success"></i>
                    </div>
                    <div class="stat-content d-inline-block">
                        <div class="stat-text">Data Radiasi</div>
                        <div class="stat-digit" id="latestCpm"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-12">
            <div class="card">
                <div class="stat-widget-one card-body">
                    <div class="stat-icon d-inline-block">
                        <i class="fa-solid fa-temperature-high text-warning border-warning"></i>
                    </div>
                    <div class="stat-content d-inline-block">
                        <div class="stat-text">Suhu</div>
                        <div class="stat-digit" id="latestTemp"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-12">
            <div class="card">
                <div class="stat-widget-one card-body">
                    <div class="stat-icon d-inline-block">
                        <i class="fas fa-droplet text-primary border-primary" style="width: 60px; height: 60px;"></i>
                    </div>
                    <div class="stat-content d-inline-block">
                        <div class="stat-text">Kelembaban</div>
                        <div class="stat-digit" id="latestHumidity"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-12">
            <div class="card">
                <div class="stat-widget-one card-body">
                    <div class="stat-icon d-inline-block">
                        <i class="ti-alert text-danger border-danger"></i>
                    </div>
                    <div class="stat-content d-inline-block">
                        <div class="stat-text">Level Radiasi</div>
                        <div class="stat-digit" id="radiationLevel"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Sensor Update ({{ $lastOnline }})</h4>
                </div>
                <div class="card-body">
                    <canvas id="intervalChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Average Condition ({{ $lastOnline }})</h4>
                </div>
                <div class="card-body">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">History Data</h4>
                </div>
                <div class="card-body">
                    <form class="mb-3" method="GET" action="{{ route('dashboard') }}">
                        <div class="d-flex">
                            <input type="text" style="width: 350px; margin-right: 10px;"
                                class="form-control input-daterange-timepicker" name="daterange"
                                value="{{ request('daterange', now()->subHours(1)->format('m/d/Y h:i A') . ' - ' . now()->format('m/d/Y h:i A')) }}">
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>
                    </form>
                    <canvas id="historyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'MM/DD/YYYY h:mm A'
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('historyChart').getContext('2d');
            var times = @json($averages->pluck('waktu'));
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: times,
                    datasets: [{
                            label: 'CPM',
                            data: @json($averages->pluck('avg_cpm')),
                            backgroundColor: "rgba(113, 88, 203, .15)",
                            borderColor: "rgba(113, 88, 203, 1)",
                            borderWidth: 1,
                        },
                        {
                            label: 'Temperature',
                            data: @json($averages->pluck('avg_temp')),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                        },
                        {
                            label: 'Humidity',
                            data: @json($averages->pluck('avg_humidity')),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    animation: {
                        duration: 0,
                    },
                    tooltips: {
                        intersect: false,
                        backgroundColor: 'rgba(113, 88, 203, 1)',
                        titleFontSize: 16,
                        titleFontStyle: '400',
                        titleSpacing: 4,
                        titleMarginBottom: 8,
                        bodyFontSize: 12,
                        bodyFontStyle: '400',
                        bodySpacing: 4,
                        xPadding: 8,
                        yPadding: 8,
                        cornerRadius: 4,
                        displayColors: false,
                        callbacks: {
                            title: function(tooltipItems, data) {
                                var idx = tooltipItems[0].index;
                                return dates[idx] + ', ' + times[idx];
                            },
                            label: function(tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex].label + ': ' +
                                    tooltipItem.yLabel;
                            },
                        },
                    },
                    title: {
                        text: 'Sensor Data',
                        display: true,
                    },
                    maintainAspectRatio: true,
                    spanGaps: false,
                    scales: {
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'Waktu',
                            },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 0,
                            },
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                },
            });
        });
    </script>

    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/chart.js') }}"></script>
@endsection
