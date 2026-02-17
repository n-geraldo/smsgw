@extends('layouts.app')

@section('title', 'Edit Gateway')

@section('content')
    <h3 class="mb-3">Edit Gateway</h3>
    <form method="POST" action="{{ route('gateways.update', $gateway) }}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Name</label>
                <input class="form-control" name="name" value="{{ old('name', $gateway->name) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Base URL</label>
                <input class="form-control" name="base_url" value="{{ old('base_url', $gateway->base_url) }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Endpoint Path</label>
                <input class="form-control" name="endpoint_path" value="{{ old('endpoint_path', $gateway->endpoint_path) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Request Type</label>
                <select class="form-select" name="request_type">
                    <option value="json" {{ $gateway->request_type === 'json' ? 'selected' : '' }}>JSON</option>
                    <option value="query" {{ $gateway->request_type === 'query' ? 'selected' : '' }}>Query String</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Auth Type</label>
                <select class="form-select" name="auth_type">
                    <option value="none" {{ $gateway->auth_type === 'none' ? 'selected' : '' }}>None</option>
                    <option value="basic" {{ $gateway->auth_type === 'basic' ? 'selected' : '' }}>Basic</option>
                    <option value="bearer" {{ $gateway->auth_type === 'bearer' ? 'selected' : '' }}>Bearer</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Sender ID</label>
                <input class="form-control" name="sender_id" value="{{ old('sender_id', $gateway->sender_id) }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Username</label>
                <input class="form-control" name="username" value="{{ old('username', $gateway->username) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Password (leave blank to keep)</label>
                <input class="form-control" name="password" type="password" value="">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Bearer Token (leave blank to keep)</label>
            <input class="form-control" name="token" value="">
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ $gateway->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default" {{ $gateway->is_default ? 'checked' : '' }}>
            <label class="form-check-label" for="is_default">Default Gateway</label>
        </div>
        <button class="btn btn-primary" type="submit">Update</button>
        <a class="btn btn-outline-secondary" href="{{ route('gateways.index') }}">Cancel</a>
    </form>
@endsection
