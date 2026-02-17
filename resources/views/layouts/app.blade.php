<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AAA SMS Gateway Manager')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f6f8fb; }
        .sidebar { width: 240px; min-height: 100vh; background: #1f2937; }
        .sidebar a { color: #d1d5db; text-decoration: none; }
        .sidebar a.active, .sidebar a:hover { color: #fff; }
        .content { background: #fff; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">AAA SMS Gateway Manager</span>
        <div class="d-flex">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
            </form>
        </div>
    </div>
</nav>
<div class="d-flex">
    <aside class="sidebar p-3">
        <nav class="nav flex-column gap-2">
            <a class="nav-link px-0 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
            <a class="nav-link px-0 {{ request()->routeIs('templates.*') ? 'active' : '' }}" href="{{ route('templates.index') }}">Templates</a>
            <a class="nav-link px-0 {{ request()->routeIs('schedules.*') ? 'active' : '' }}" href="{{ route('schedules.index') }}">Schedules</a>
            <a class="nav-link px-0 {{ request()->routeIs('gateways.*') ? 'active' : '' }}" href="{{ route('gateways.index') }}">Gateways</a>
            <a class="nav-link px-0 {{ request()->routeIs('dma.config') ? 'active' : '' }}" href="{{ route('dma.config') }}">DMA Config</a>
            <a class="nav-link px-0 {{ request()->routeIs('dma.mapping') ? 'active' : '' }}" href="{{ route('dma.mapping') }}">DMA Mapping</a>
            <a class="nav-link px-0 {{ request()->routeIs('sms.monitor*') ? 'active' : '' }}" href="{{ route('sms.monitor') }}">SMS Monitor</a>
            <a class="nav-link px-0 {{ request()->routeIs('sms.test*') ? 'active' : '' }}" href="{{ route('sms.test') }}">Test SMS</a>
        </nav>
    </aside>
    <main class="flex-grow-1 p-4">
        @if (session('status'))
            <div class="alert alert-info">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="content p-4">
            @yield('content')
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
