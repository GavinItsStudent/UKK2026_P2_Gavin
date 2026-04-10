@extends('Layout.home')

@section('content')
<div class="container-xxl container-p-y">

    <h4 class="fw-bold mb-4">Log Aktivitas</h4>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->user->username ?? '-' }}</td>
                        <td>{{ $log->aktivitas }}</td>
                        <td>{{ $log->waktu_aktivitas }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection