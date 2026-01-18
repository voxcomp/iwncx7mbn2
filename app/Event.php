<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'short', 'event_date', 'description', 'image', 'goal', 'user_id', 'event_id',
    ];

    /**
     * Gets donations attributed to event
     */
    public function donations(): HasMany
    {
        return $this->hasMany(\App\Donation::class)->whereDate('created_at', '<=', Carbon::today());
    }

    /**
     * Gets participants for event
     */
    public function participants(): HasMany
    {
        return $this->hasMany(\App\Registrant::class);
    }

    /**
     * Gets teams for event
     */
    public function teams(): HasMany
    {
        return $this->hasMany(\App\Team::class);
    }

    /**
     * Gets sponsors for event
     */
    public function sponsors(): HasMany
    {
        return $this->hasMany(\App\Sponsor::class);
    }

    /**
     * Gets sponsor submissions for event
     */
    public function sponsorSubmissions(): HasMany
    {
        return $this->hasMany(\App\SponsorSubmission::class);
    }

    /**
     * Gets volunteer submissions for event
     */
    public function volunteerSubmissions(): HasMany
    {
        return $this->hasMany(\App\VolunteerSubmission::class);
    }

    /**
     * Gets costs for event
     */
    public function costs(): HasMany
    {
        return $this->hasMany(\App\Cost::class);
    }

    /**
     * Gets donations and signups for event
     */
    public function raised(): int
    {
        $total = $this->participants->sum('paid');
        $total += $this->sponsorSubmissions->sum('paid');
        $total += $this->sponsorSubmissions->sum('inkind_value');
        $total += $this->donations->sum('amount');
        // $total += \App\Donation::whereDate('created_at', ">=", new Carbon("june 22, 2025"))->get()->sum('amount');
        // ->whereDate('created_at',"<",Carbon::today()->addDay())
        // where('event_id',0)->where('team_id',0)->where('registrant_id',0)->

        return $total;
    }

    /**
     * Gets percentage of goal for event
     */
    public function percent(): int
    {
        $percent = round(($this->raised() / $this->goal) * 100);

        return ($percent > 100) ? 100 : $percent;
    }

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'short',
            ],
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
