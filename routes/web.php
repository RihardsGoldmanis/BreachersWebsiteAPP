<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::post('/teams/{team}/invite', [TeamController::class, 'invite'])->name('teams.invite');
    Route::post('/teams/{team}/delete', [TeamController::class, 'delete'])->name('teams.delete');
    Route::post('/teams/{team}/remove-member', [TeamController::class, 'removeMember'])->name('teams.removeMember');
    Route::post('/teams/leave', [TeamController::class, 'leave'])->name('teams.leave');
    Route::post('/teams/{team}/add-member', [TeamController::class, 'addMember'])->name('teams.addMember');
    Route::get('/teams/overview', [TeamController::class, 'overview'])->name('teams.overview');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
});

Route::match(['get', 'post'], '/player/search', [App\Http\Controllers\TriangleFactoryController::class, 'playerSearch'])->name('player.search');

require __DIR__.'/auth.php';
