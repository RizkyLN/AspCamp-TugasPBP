<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Suara UNISNU')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="/">Suara UNISNU</a>
        <ul class="navbar-nav me-auto">
            @auth
                @if (auth()->user()->role === 'pengguna')
                    <li class="nav-item"><a class="nav-link" href="{{ route('suara.index') }}">Suara Saya</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('suara.create') }}">Kirim Suara</a></li>
                @endif
                @if (auth()->user()->role === 'pengelola')
                    <li class="nav-item"><a class="nav-link" href="{{ route('pengelola.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pengelola.suara.index') }}">Kelola Suara</a></li>
                @endif
            @endauth
            <li class="nav-item"><a class="nav-link" href="{{ route('anonim.form') }}">Suara Anonim</a></li>
        </ul>
        @auth
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('profil.edit') }}" class="d-flex align-items-center gap-2 text-white text-decoration-none small">
                    <x-avatar :user="auth()->user()" :size="28" />
                    {{ auth()->user()->username }}
                    <x-status-icon :status="auth()->user()->status_akun" />
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-light">Keluar</button>
                </form>
            </div>
        @endauth
    </div>
</nav>
<div class="container">
    @yield('content')
</div>
@stack('scripts')
</body>
</html>