@extends('layouts.app')

@section('title', 'Test SMS')

@section('content')
    <h3 class="mb-3">Test SMS</h3>

    <form method="POST" action="{{ route('sms.test.send') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone</label>
                <input class="form-control" name="phone" value="{{ old('phone') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Template</label>
                <select class="form-select" name="template_id" required>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Gateway (optional)</label>
            <select class="form-select" name="gateway_id">
                <option value="">Default Active Gateway</option>
                @foreach ($gateways as $gateway)
                    <option value="{{ $gateway->id }}">{{ $gateway->name }}{{ $gateway->is_default ? ' (default)' : '' }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary" type="submit">Send Test SMS</button>
    </form>
@endsection
