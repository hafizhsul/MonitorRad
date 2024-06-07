@extends('Partials.main')

@section('content')
    <div id="alert-container"></div>
    {{-- <div class="alert alert-warning solid alert-right-icon alert-dismissible fade show">
        <span><i class="mdi mdi-alert"></i></span>
        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i
                    class="mdi mdi-close"></i></span>
        </button>
        <strong>Peringatan!</strong> Tingkat radiasi tinggi di sekitar.
    </div> --}}
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
                    <h4 class="card-title">Sensor Update ({{ $waktu }})</h4>
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
                    <h4 class="card-title">Average Condition ({{ $waktu }})</h4>
                </div>
                <div class="card-body">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/chart.js') }}"></script>
@endsection
