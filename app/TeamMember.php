<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'registrant_id', 'team_id',
    ];

    public function registrant()
    {
        return $this->belongsTo('App\Registrant');
    }

    public function team()
    {
        return $this->belongsTo('App\Team');
    }
}
