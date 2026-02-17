@extends('layouts.app')

@section('title', 'Gateways')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Gateways</h3>
        <a class="btn btn-primary" href="{{ route('gateways.create') }}">New Gateway</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Base URL</th>
                <th>Auth</th>
                <th>Request</th>
                <th>Active</th>
                <th>Default</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($gateways as $gateway)
                <tr>
                    <td>{{ $gateway->name }}</td>
                    <td>{{ $gateway->base_url }}</td>
                    <td>{{ $gateway->auth_type }}</td>
                    <td>{{ $gateway->request_type }}</td>
                    <td>{{ $gateway->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $gateway->is_default ? 'Yes' : 'No' }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('gateways.edit', $gateway) }}">Edit</a>
                        <form method="POST" action="{{ route('gateways.destroy', $gateway) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete gateway?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-muted">No gateways.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $gateways->links() }}
@endsection
