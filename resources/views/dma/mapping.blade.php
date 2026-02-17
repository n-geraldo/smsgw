@extends('layouts.app')

@section('title', 'DMA Mapping')

@section('content')
    <h3 class="mb-3">DMA Mapping</h3>
    <form method="POST" action="{{ route('dma.mapping.update') }}" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Table</label>
                <input class="form-control" name="table" value="{{ old('table', $mapping['table'] ?? '') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Username Column</label>
                <input class="form-control" name="username_column" value="{{ old('username_column', $mapping['username_column'] ?? '') }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone Column</label>
                <input class="form-control" name="phone_column" value="{{ old('phone_column', $mapping['phone_column'] ?? '') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Expiration Column</label>
                <input class="form-control" name="expiration_column" value="{{ old('expiration_column', $mapping['expiration_column'] ?? '') }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Status Column (optional)</label>
                <input class="form-control" name="status_column" value="{{ old('status_column', $mapping['status_column'] ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Active Values (comma separated)</label>
                <input class="form-control" name="status_active_values" value="{{ old('status_active_values', $mapping['status_active_values'] ?? '') }}">
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Save Mapping</button>
        <button class="btn btn-outline-secondary" type="submit" formaction="{{ route('dma.mapping.test') }}">Test Query</button>
    </form>

    @if ($sample->count())
        <h5 class="mb-3">Sample Results</h5>
        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Phone</th>
                    <th>Expiration</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($sample as $row)
                    <tr>
                        <td>{{ $row['username'] }}</td>
                        <td>{{ $row['phone'] }}</td>
                        <td>{{ optional($row['expiration_date'])->format('Y-m-d') }}</td>
                        <td>{{ $row['status'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
