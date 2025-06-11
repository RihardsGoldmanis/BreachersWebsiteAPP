<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamInvitation extends Model
{
    protected $fillable = ['team_id', 'invitee_email', 'status'];

    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}
