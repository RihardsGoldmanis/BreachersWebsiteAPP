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
    public function removeMember(Request $request, $teamId)
{
    $team = \App\Models\Team::findOrFail($teamId);
    if ($team->owner_email !== Auth::user()->email) abort(403);

    $request->validate(['member_email' => 'required|email']);

    \App\Models\TeamMember::where('team_id', $teamId)
        ->where('member_email', $request->member_email)
        ->delete();

    return back()->with('success', 'Member removed!');
}
    public function leaveTeam(Request $request)
{
    $member = \App\Models\TeamMember::where('member_email', Auth::user()->email)->first();
    if ($member) {
        $team = $member->team;
        if ($team && $team->owner_email === Auth::user()->email) {
            return back()->with('error', 'Owners cannot leave their own team. Delete the team instead.');
        }
        $member->delete();
        return back()->with('success', 'You left the team.');
    }
    return back()->with('error', 'You are not a member of any team.');
}
    public function delete(Request $request, $teamId)
{
    $team = \App\Models\Team::findOrFail($teamId);
    if ($team->owner_email !== Auth::user()->email) abort(403);

    $team->delete();
    return redirect()->route('teams.index')->with('success', 'Team deleted.');
}
public function addMember(Request $request, $teamId)
{
    $request->validate([
        'member_email' => 'required|email',
    ]);

    $team = \App\Models\Team::findOrFail($teamId);


    $alreadyInTeam = \App\Models\TeamMember::where('member_email', $request->member_email)->exists();

    if ($alreadyInTeam) {

        return back()->with('error', 'User is already in a team.');
    } else {
        \App\Models\TeamMember::create([
            'team_id' => $teamId,
            'member_email' => $request->member_email,
        ]);
        return back()->with('success', 'Member added to team!');
    }
}




}
