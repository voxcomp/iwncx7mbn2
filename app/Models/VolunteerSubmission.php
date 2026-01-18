<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerSubmission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'company', 'fname', 'lname', 'email', 'phone', 'address', 'city', 'state', 'zip',
    ];
}
