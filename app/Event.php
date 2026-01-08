<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Event extends Model
{
	use Sluggable;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'short', 'event_date', 'description', 'image', 'goal', 'user_id', 'event_id'
    ];

    /**
     * Gets donations attributed to event
     *
     * @return App\Donation
     */
	public function donations() {
	    return $this->hasMany('App\Donation')->whereDate('created_at','<=',Carbon::today());
	}

    /**
     * Gets participants for event
     *
     * @return App\Participant
     */
    public function participants() {
	    return $this->hasMany('App\Registrant');
    } 
    
    /**
     * Gets teams for event
     *
     * @return App\Team
     */
    public function teams() {
	    return $this->hasMany('App\Team');
    } 

    /**
     * Gets sponsors for event
     *
     * @return App\Sponsor
     */
    public function sponsors() {
	    return $this->hasMany('App\Sponsor');
    } 

    /**
     * Gets sponsor submissions for event
     *
     * @return App\SponsorSubmission
     */
    public function sponsorSubmissions() {
	    return $this->hasMany('App\SponsorSubmission');
    } 

    /**
     * Gets volunteer submissions for event
     *
     * @return App\VolunteerSubmission
     */
    public function volunteerSubmissions() {
	    return $this->hasMany('App\VolunteerSubmission');
    } 

    /**
     * Gets costs for event
     *
     * @return App\Cost
     */
    public function costs() {
	    return $this->hasMany('App\Cost');
    } 

    /**
     * Gets donations and signups for event
     *
     * @return integer
     */
    public function raised() {
	    $total = $this->participants->sum('paid');
	    $total += $this->sponsorSubmissions->sum('paid');
	    $total += $this->sponsorSubmissions->sum('inkind_value');
        $total += $this->donations->sum('amount');
	    //$total += \App\Donation::whereDate('created_at', ">=", new Carbon("june 22, 2025"))->get()->sum('amount');
        //->whereDate('created_at',"<",Carbon::today()->addDay())
            //where('event_id',0)->where('team_id',0)->where('registrant_id',0)->
	    
	    return $total;
    } 

    /**
     * Gets percentage of goal for event
     *
     * @return integer
     */
    public function percent() {
	    $percent = round(($this->raised()/$this->goal)*100);

	    return ($percent>100)?100:$percent;
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
                'source' => 'short'
            ]
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
