<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-sports App') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex flex-column">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                E-sports App
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('player.search') }}">Player Search</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('teams.index') }}">Team Overview</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://www.triangle-factory.be/">Triangle Factory </a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">{{ Auth::user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <main class="container flex-fill py-4">

        @auth
            @php
                $pendingInvitations = \App\Models\TeamInvitation::where('invitee_email', Auth::user()->email)
                    ->where('status', 'pending')
                    ->with('team')
                    ->get();
            @endphp

            @if($pendingInvitations->count())
                <div class="modal fade show" tabindex="-1" style="display:block; background:rgba(0,0,0,0.4)">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Team Invitations</h5>
                            </div>
                            <div class="modal-body">
                                @foreach($pendingInvitations as $inv)
                                    <div class="mb-2">
                                        <strong>{{ $inv->team->name }}</strong> invited you.<br>
                                        <form action="{{ route('invitations.respond', $inv->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                                            <button name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        @yield('content')
    </main>
    <footer class="text-center mt-auto mb-3 text-muted small">
     {{ date('Y') }} Rihards Goldmanis &mdash; E-sports App
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
