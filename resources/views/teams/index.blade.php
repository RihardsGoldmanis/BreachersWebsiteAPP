@extends('layouts.app')

@php
    $hasTeam = $teams->where('owner_email', Auth::user()->email)->count() > 0;
    $myTeamMember = \App\Models\TeamMember::where('member_email', Auth::user()->email)->first();
    $myTeam = $myTeamMember?->team;
    $isAdmin = Auth::user()->is_admin ?? false;
@endphp

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Teams</h1>
        @if(!$hasTeam || $isAdmin)
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

                        @if($isAdmin || $team->owner_email === Auth::user()->email)
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
                                        <li class="text-muted small d-flex align-items-center justify-content-between">
                                            <span>{{ $member->member_email }}</span>
                                            {{-- Remove member (admin can always, owner if not self) --}}
                                            @if(
                                                ($isAdmin && $member->member_email !== $team->owner_email) ||
                                                ($team->owner_email === Auth::user()->email && $member->member_email !== Auth::user()->email)
                                            )
                                                <form action="{{ route('teams.removeMember', $team->id) }}" method="POST" class="ms-2">
                                                    @csrf
                                                    <input type="hidden" name="member_email" value="{{ $member->member_email }}">
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to remove this member?');">
                                                        Remove
                                                    </button>
                                                </form>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($isAdmin)
                            <form method="POST" action="{{ route('teams.addMember', $team->id) }}" class="d-flex mt-2">
                                @csrf
                                <input type="email" name="member_email" class="form-control me-2" placeholder="Add member by email" required>
                                <button type="submit" class="btn btn-outline-success btn-sm">Add Member</button>
                            </form>
                        @endif

                    
                        @if(
                            ($myTeam && $myTeam->id === $team->id && $team->owner_email !== Auth::user()->email) ||
                            ($isAdmin && $myTeam && $myTeam->id === $team->id)
                        )
                            <form action="{{ route('teams.leave') }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm w-100"
                                    onclick="return confirm('Are you sure you want to leave this team?');">
                                    Leave Team
                                </button>
                            </form>
                        @endif

                    
                        @if($isAdmin || $team->owner_email === Auth::user()->email)
                            <button type="button" class="btn btn-danger btn-sm w-100 mt-3"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteTeamModal{{ $team->id }}">
                                Delete Team
                            </button>

                            <div class="modal fade" id="deleteTeamModal{{ $team->id }}" tabindex="-1" aria-labelledby="deleteTeamModalLabel{{ $team->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <form method="POST" action="{{ route('teams.delete', $team->id) }}">
                                    @csrf
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="deleteTeamModalLabel{{ $team->id }}">Confirm Team Deletion</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <p>
                                        To delete this team, please type: <br>
                                        <strong>delete {{ $team->name }}</strong>
                                      </p>
                                      <input type="text" class="form-control" name="team_delete_confirm"
                                          id="teamDeleteConfirmInput{{ $team->id }}"
                                          placeholder="delete {{ $team->name }}"
                                          required
                                          oninput="checkDeleteInput{{ $team->id }}(this)">
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                      <button id="confirmDeleteButton{{ $team->id }}" type="submit" class="btn btn-danger" disabled>Delete</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <script>
                            function checkDeleteInput{{ $team->id }}(input) {
                                var confirmBtn = document.getElementById('confirmDeleteButton{{ $team->id }}');
                                if (input.value === 'delete {{ $team->name }}') {
                                    confirmBtn.disabled = false;
                                } else {
                                    confirmBtn.disabled = true;
                                }
                            }
                            </script>
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
