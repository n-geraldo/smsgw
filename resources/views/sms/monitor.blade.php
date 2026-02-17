@extends('layouts.app')

@section('title', 'SMS Monitor')

@section('content')
    <h3 class="mb-3">SMS Monitor</h3>

    <form class="row g-2 mb-3" method="GET" action="{{ route('sms.monitor') }}">
        <div class="col-md-2">
            <input class="form-control" name="username" placeholder="Username" value="{{ request('username') }}">
        </div>
        <div class="col-md-2">
            <input class="form-control" name="phone" placeholder="Phone" value="{{ request('phone') }}">
        </div>
        <div class="col-md-2">
            <select class="form-select" name="status">
                <option value="">Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" name="template_id">
                <option value="">Template</option>
                @foreach ($templates as $template)
                    <option value="{{ $template->id }}" {{ (string) request('template_id') === (string) $template->id ? 'selected' : '' }}>{{ $template->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input class="form-control" type="date" name="from" value="{{ request('from') }}">
        </div>
        <div class="col-md-2">
            <input class="form-control" type="date" name="to" value="{{ request('to') }}">
        </div>
        <div class="col-md-12">
            <button class="btn btn-outline-primary" type="submit">Filter</button>
            <a class="btn btn-outline-secondary" href="{{ route('sms.monitor') }}">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Template</th>
                <th>Status</th>
                <th>Scheduled</th>
                <th class="text-end">Details</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($jobs as $job)
                <tr>
                    <td>{{ $job->id }}</td>
                    <td>{{ $job->customer_username }}</td>
                    <td>{{ $job->phone }}</td>
                    <td>{{ $job->template?->name }}</td>
                    <td>{{ $job->status }}</td>
                    <td>{{ $job->scheduled_for }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('sms.monitor.show', $job) }}">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-muted">No jobs found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $jobs->links() }}
@endsection
