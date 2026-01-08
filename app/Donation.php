<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname', 'lname', 'email', 'phone', 'address', 'city', 'state', 'zip', 'join', 'amount', 'event_id', 'registrant_id', 'team_id', 'paytype', 'anonymous', 'payid', 'recurring', 'customerid', 'planid', 'subscriptionid', 'cancelled_on', 'recurring_amount', 'promise', 'memoryof', 'photo', 'cause',
    ];

    public function registrant()
    {
        return $this->belongsTo(\App\Registrant::class);
    }

    public function team()
    {
        return $this->belongsTo(\App\Team::class);
    }

    public function event()
    {
        return $this->belongsTo(\App\Event::class);
    }
}
