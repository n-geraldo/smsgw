@extends('layouts.app')

@section('title', 'SMS Job')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">SMS Job #{{ $job->id }}</h3>
        <a class="btn btn-outline-secondary" href="{{ route('sms.monitor') }}">Back</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="mb-2"><strong>Username:</strong> {{ $job->customer_username }}</div>
            <div class="mb-2"><strong>Phone:</strong> {{ $job->phone }}</div>
            <div class="mb-2"><strong>Template:</strong> {{ $job->template?->name }}</div>
            <div class="mb-2"><strong>Status:</strong> {{ $job->status }}</div>
        </div>
        <div class="col-md-6">
            <div class="mb-2"><strong>Scheduled:</strong> {{ $job->scheduled_for }}</div>
            <div class="mb-2"><strong>Expiration:</strong> {{ $job->expiration_date }}</div>
            <div class="mb-2"><strong>Attempts:</strong> {{ $job->attempt_count }}</div>
            <div class="mb-2"><strong>Last Error:</strong> {{ $job->last_error }}</div>
            <div class="mb-2"><strong>Provider ID:</strong> {{ $job->provider_message_id }}</div>
            <div class="mb-2"><strong>Sent At:</strong> {{ $job->sent_at }}</div>
        </div>
    </div>

    <h5 class="mb-3">Logs</h5>
    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>HTTP</th>
                <th>Created</th>
                <th>Request</th>
                <th>Response</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($job->logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->http_status }}</td>
                    <td>{{ $log->created_at }}</td>
                    <td><pre class="mb-0">{{ json_encode($log->request_payload, JSON_PRETTY_PRINT) }}</pre></td>
                    <td><pre class="mb-0">{{ json_encode($log->response_payload, JSON_PRETTY_PRINT) }}</pre></td>
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
