@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Create a New Team</h1>
    <form method="POST" action="{{ route('teams.store') }}" class="card p-4 shadow-sm" style="max-width:400px">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Team Name</label>
            <input id="name" name="name" type="text" class="form-control" required autofocus>
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="logo_url" class="form-label">Team Logo URL (optional)</label>
            <input id="logo_url" name="logo_url" type="url" class="form-control" placeholder="https://example.com/logo.gif">
            @error('logo_url')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create Team</button>
    </form>
</div>
@endsection
