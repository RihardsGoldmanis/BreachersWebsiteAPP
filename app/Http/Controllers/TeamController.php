<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamMember;


class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('created_at', 'desc')->get();
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:100|unique:teams,name',
        'logo_url' => 'nullable|url',
    ]);

    $existingTeam = \App\Models\Team::where('owner_email', Auth::user()->email)->first();
    if ($existingTeam) {
        return redirect()->route('teams.index')->with('error', 'You can only create one team.');
    }

    Team::create([
        'name' => $request->name,
        'owner_email' => Auth::user()->email,
        'logo_url' => $request->logo_url,
    ]);

    return redirect()->route('teams.index')->with('success', 'Team created!');
}
public function invite(Request $request, $teamId)
    {
        $request->validate(['email' => 'required|email']);
        $team = Team::findOrFail($teamId);
        if ($team->owner_email !== Auth::user()->email) {
            abort(403);
        }

        TeamInvitation::create([
            'team_id' => $team->id,
            'invitee_email' => $request->email,
        ]);

        return back()->with('success', 'Invitation sent!');
    }

    public function respondToInvitation(Request $request, $invitationId)
{
    $invitation = TeamInvitation::findOrFail($invitationId);

    if ($invitation->invitee_email !== Auth::user()->email) {
        abort(403);
    }

    if ($request->action == 'accept') {
        $alreadyInTeam = TeamMember::where('member_email', Auth::user()->email)->exists();
        if ($alreadyInTeam) {
            return redirect()->route('dashboard')->with('error', 'You can only join one team at a time.');
        }

        $invitation->status = 'accepted';
        TeamMember::create([
            'team_id' => $invitation->team_id,
            'member_email' => Auth::user()->email,
        ]);
        TeamInvitation::where('invitee_email', Auth::user()->email)
            ->where('status', 'pending')
            ->where('id', '!=', $invitation->id)
            ->update(['status' => 'rejected']);
    } else {
        $invitation->status = 'rejected';
    }
    $invitation->save();

    return redirect()->route('dashboard')->with('success', 'Response recorded.');
}
}
