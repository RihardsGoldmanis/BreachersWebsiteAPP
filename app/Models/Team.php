<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_email',
        'logo_url',
    ];
    public function members()
{
    return $this->hasMany(\App\Models\TeamMember::class);
}

}
