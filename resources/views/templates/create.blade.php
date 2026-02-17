@extends('layouts.app')

@section('title', 'Create Template')

@section('content')
    <h3 class="mb-3">Create Template</h3>
    <form method="POST" action="{{ route('templates.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input class="form-control" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Trigger Type</label>
            <select class="form-select" name="trigger_type">
                <option value="days_before_expiry">Days Before Expiry</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Days Before</label>
            <input class="form-control" type="number" name="days_before" value="{{ old('days_before', 1) }}" min="0" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Message Body</label>
            <textarea class="form-control" name="message_body" rows="4" required>{{ old('message_body') }}</textarea>
            <div class="form-text">Placeholders: {username}, {expiry_date}, {days_left}, {company_name}</div>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a class="btn btn-outline-secondary" href="{{ route('templates.index') }}">Cancel</a>
    </form>
@endsection
