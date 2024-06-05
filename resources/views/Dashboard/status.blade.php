@extends('Partials.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tabel Status Radiasi</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="display" style="min-width: 845px">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Waktu</th>
                                    <th>CPM</th>
                                    <th>Suhu</th>
                                    <th>Kelembaban</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->waktu }}</td>
                                        <td>{{ $item->cpm }}</td>
                                        <td>{{ $item->temp }}</td>
                                        <td>{{ $item->humidity }}</td>
                                        <td><span class="btn btn-danger">Tinggi</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
