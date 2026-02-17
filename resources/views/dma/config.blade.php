@extends('layouts.app')

@section('title', 'DMA Config')

@section('content')
    <h3 class="mb-3">DMA Connection</h3>
    <p class="text-muted">Connection settings are stored in <code>.env</code>. Update the values below in your environment and reload the app.</p>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Host</label>
            <input class="form-control" value="{{ $config['host'] ?? '' }}" readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Port</label>
            <input class="form-control" value="{{ $config['port'] ?? '' }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Database</label>
            <input class="form-control" value="{{ $config['database'] ?? '' }}" readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Username</label>
            <input class="form-control" value="{{ $config['username'] ?? '' }}" readonly>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input class="form-control" value="{{ $masked['password'] ?? '' }}" readonly>
    </div>

    <form method="POST" action="{{ route('dma.config.test') }}">
        @csrf
        <button class="btn btn-outline-primary" type="submit">Test Connection</button>
    </form>
@endsection
