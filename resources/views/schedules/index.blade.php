@extends('layouts.app')

@section('title', 'Schedules')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Schedules</h3>
        <a class="btn btn-primary" href="{{ route('schedules.create') }}">New Schedule</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Template</th>
                <th>Run Hour</th>
                <th>Timezone</th>
                <th>Active</th>
                <th>Last Run</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->template?->name }}</td>
                    <td>{{ $schedule->run_hour }}</td>
                    <td>{{ $schedule->timezone }}</td>
                    <td>{{ $schedule->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $schedule->last_run_at }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('schedules.edit', $schedule) }}">Edit</a>
                        <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete schedule?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-muted">No schedules.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $schedules->links() }}
@endsection
