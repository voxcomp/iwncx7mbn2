<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Registrant extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname', 'lname', 'email', 'phone', 'address', 'city', 'state', 'zip', 'pets', 'registrant', 'shirt', 'user_id', 'paid', 'moderated', 'event_id', 'reviewed', 'adminnotes', 'pagecontent', 'pagetitle', 'pageurl', 'pageshorturl', 'goal', 'discountcode', 'payid', 'shipshirt', 'shipaddress', 'shipcity', 'shipstate', 'shipzip',
    ];

    /**
     * Gets donations attributed to user/participant, can be searched for event
     *
     * @return App\Donation
     */
    public function donations()
    {
        return $this->hasMany('App\Donation');
    }

    public function eventDonations(Event $event)
    {
        return $this->donations->where('event_id', $event->id)->sum('amount');
    }

    public function eventDonationPercent(Event $event)
    {
        $donations = $this->eventDonations($event);
        $percent = ($this->goal == 0) ? 0 : round(($donations / $this->goal) * 100);

        return ($percent > 100) ? 100 : $percent;
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function isParticipant(Event $event)
    {
        return $this->event->title == $event->title;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function teams()
    {
        return $this->hasMany('App\TeamMember');
    }

    public function team()
    {
        return $this->hasOne('App\Team');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['fname', 'lname'],
                'separator' => '-',
            ],
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
