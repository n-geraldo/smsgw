@extends('layouts.app')

@section('title', 'Create Schedule')

@section('content')
    <h3 class="mb-3">Create Schedule</h3>
    <form method="POST" action="{{ route('schedules.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Template</label>
            <select class="form-select" name="template_id" required>
                @foreach ($templates as $template)
                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Run Hour (0-23)</label>
            <input class="form-control" type="number" name="run_hour" value="{{ old('run_hour', 9) }}" min="0" max="23" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Timezone</label>
            <input class="form-control" name="timezone" value="{{ old('timezone', config('app.timezone')) }}" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
        <a class="btn btn-outline-secondary" href="{{ route('schedules.index') }}">Cancel</a>
    </form>
@endsection
