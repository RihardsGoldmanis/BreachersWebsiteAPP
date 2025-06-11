@extends('layouts.app')

@php
    $hasTeam = $teams->where('owner_email', Auth::user()->email)->count() > 0;
@endphp

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Teams</h1>
        @if(!$hasTeam)
            <a href="{{ route('teams.create') }}" class="btn btn-primary">Create New Team</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @forelse($teams as $team)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm text-center">
<div class="card-body">
    <img src="{{ $team->logo_url ?? 'https://media.tenor.com/1DmQO5T1VJkAAAAd/matrix-neo.gif' }}"
         alt="Logo"
         class="rounded mb-3"
         style="width:70px;height:70px;object-fit:cover;">
    <h5 class="card-title">{{ $team->name }}</h5>
    <div class="text-muted small mb-2">by {{ $team->owner_email }}</div>

    @if($team->owner_email === Auth::user()->email)
        <form method="POST" action="{{ route('teams.invite', $team->id) }}" class="mb-2 d-flex">
            @csrf
            <input type="email" name="email" class="form-control me-2" placeholder="Invite by email" required>
            <button type="submit" class="btn btn-outline-primary btn-sm">Invite</button>
        </form>
    @endif

    @if($team->members->count())
        <hr>
        <div>
            <span class="fw-bold">Members:</span>
            <ul class="list-unstyled mb-0">
                @foreach($team->members->take(10) as $member)
                    <li class="text-muted small">{{ $member->member_email }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">No teams found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
