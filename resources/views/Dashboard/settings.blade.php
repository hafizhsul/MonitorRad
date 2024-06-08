@extends('Partials.main')

@section('content')
    <div id="alert-container"></div>

    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="stat-widget-two card-body">
                    <div class="stat-content">
                        <div class="stat-text">Status Device</div>
                        <div id="device-status" class="stat-digit">Loading...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="stat-widget-two card-body">
                    <div class="stat-content">
                        <div class="stat-text">Terakhir Online</div>
                        <div id="device-online" class="stat-digit">Loading...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateDeviceStatus() {
            fetch('/settings/device/status')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('device-status').innerText = data.status;
                    document.getElementById('device-online').innerText = data.lastOnline;
                });
        }

        setInterval(updateDeviceStatus, 5000);

        document.addEventListener('DOMContentLoaded', updateDeviceStatus);
    </script>
    <script src="{{ asset('js/alert.js') }}"></script>
@endsection
