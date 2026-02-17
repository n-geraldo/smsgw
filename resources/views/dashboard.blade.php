@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h3 class="mb-4">Dashboard</h3>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-success text-white">
                <div class="card-body">
                    <div class="fs-5">Sent Today</div>
                    <div class="fs-2 fw-bold">{{ $sentToday }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-danger text-white">
                <div class="card-body">
                    <div class="fs-5">Failed Today</div>
                    <div class="fs-2 fw-bold">{{ $failedToday }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-warning">
                <div class="card-body">
                    <div class="fs-5">Pending</div>
                    <div class="fs-2 fw-bold">{{ $pending }}</div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3">Latest Logs</h5>
    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Job</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->sms_job_id }}</td>
                    <td>{{ $log->job?->phone }}</td>
                    <td>{{ $log->http_status }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-muted">No logs yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
