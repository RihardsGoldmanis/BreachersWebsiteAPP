<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;

Route::get('/', function () {
    return view('dashboard');
});

require __DIR__.'/auth.php';

Route::view('/player/search', 'player_search')->name('player.search');
Route::view('/player/stats', 'player_stats')->name('player.stats');

Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::post('/teams/{team}/invite', [TeamController::class, 'invite'])->name('teams.invite');
    Route::post('/invitations/{invitation}/respond', [TeamController::class, 'respondToInvitation'])->name('invitations.respond');
});

Route::middleware(['auth'])->group(function () {
    Route::view('/admin/user-search', 'admin_user_search')->name('admin.user.search');
    Route::view('/admin/user-manage', 'admin_user_manage')->name('admin.user.manage');
    Route::view('/admin/team-manage', 'admin_team_manage')->name('admin.team.manage');
});
