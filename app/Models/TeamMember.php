<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = ['team_id', 'member_email'];

    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}
