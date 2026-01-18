<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function registrant(): BelongsTo
    {
        return $this->belongsTo(\App\Registrant::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(\App\Team::class);
    }
}
