<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'registrant_id', 'event_id', 'moderated', 'reviewed', 'adminnotes', 'pagecontent', 'pagetitle', 'pageurl', 'pageshorturl', 'goal',
    ];

    public function members()
    {
        return $this->hasMany(\App\TeamMember::class);
    }

    public function event()
    {
        return $this->belongsTo(\App\Event::class, 'event_id', 'id');
    }

    public function captain()
    {
        return $this->belongsTo(\App\Registrant::class, 'registrant_id', 'id');
    }

    public function donations()
    {
        return $this->hasMany(\App\Donation::class);
    }

    public function eventDonations(Event $event)
    {
        $total = $this->donations->where('event_id', $event->id)->sum('amount');
        foreach ($this->members as $member) {
            $total += $member->registrant->eventDonations($event);
        }

        return $total;
    }

    public function eventDonationPercent(Event $event)
    {
        $donations = $this->eventDonations($event);
        if ($this->goal > 0) {
            $percent = round(($donations / $this->goal) * 100);
        } else {
            $percent = 100;
        }

        return ($percent > 100) ? 100 : $percent;
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
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
