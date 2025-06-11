@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 700px;">
    <h2>Search Player</h2>
    <form method="POST" action="{{ route('player.search') }}">
        @csrf
        <input type="text" name="player_name" class="form-control mb-2" placeholder="Enter player name(s)" required >
        <button type="submit" class="btn btn-primary" disabled>Search</button>
    </form>
    @if(session('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif

    @if(isset($players['players']))
        <h3 class="mt-4">Results:</h3>
        @if(count($players['players']) > 0)
            <ul class="list-group">
                @foreach($players['players'] as $player)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <strong>{{ $player['name'] ?? '' }}</strong>
                            <small class="text-muted">({{ $player['player_id'] ?? '' }})</small>
                        </span>
                        {{-- You could add more actions/buttons here --}}
                    </li>
                @endforeach
            </ul>
        @else
            <div class="alert alert-info mt-2">No players found.</div>
        @endif
    @endif
</div>
@endsection
