<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-sports App') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <a href="/">
                        <svg width="60" height="60" fill="currentColor" class="text-primary mb-2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M8 12l2 2 4-4" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                    </a>
                    <h1 class="h3 fw-bold text-primary">E-sports App</h1>
                    <p class="text-secondary">Login or Register below</p>
                </div>
                <div class="card shadow-lg border-0 rounded-4 p-4">
                    {{ $slot }}
                </div>
                <div class="text-center mt-4 text-muted small">
                    &copy; {{ date('Y') }} Rihards Goldmanis &mdash; E-sports App
                </div>
            </div>
        </div>
    </div>
</body>
</html>
