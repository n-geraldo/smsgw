@extends('layouts.app')

@section('title', 'Templates')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Templates</h3>
        <a class="btn btn-primary" href="{{ route('templates.create') }}">New Template</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Days Before</th>
                <th>Active</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($templates as $template)
                <tr>
                    <td>{{ $template->name }}</td>
                    <td>{{ $template->days_before }}</td>
                    <td>{{ $template->is_active ? 'Yes' : 'No' }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('templates.edit', $template) }}">Edit</a>
                        <form method="POST" action="{{ route('templates.destroy', $template) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete template?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-muted">No templates.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $templates->links() }}
@endsection
