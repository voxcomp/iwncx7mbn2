<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorSubmission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'company', 'fname', 'lname', 'email', 'phone', 'address', 'city', 'state', 'zip', 'level', 'paid', 'image', 'paytype', 'inkind_value',
    ];
}
