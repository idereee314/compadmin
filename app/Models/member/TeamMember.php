<?php

namespace member;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use user\CompadUser as User;
use event\Event;
use event\EventTeamRegistration;

use Auth;
use Carbon;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    protected $table = 'uq_team_registration_member';
    protected $appends = array('fullname');

    public static function rules($id) 
    {
		return array(
		);
	}
    
    public function getFullnameAttribute()
    {
		return $this->lastname.' '.$this->firstname;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function member()
    {
        return $this->belongsTo('member\Member', 'member_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function EventTeamRegistration()
    {
        return $this->hasMany(EventTeamRegistration::class, 'team_id');
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function($TeamMember)
        {
            $TeamMember->updated_by = Auth::id();
			$TeamMember->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($TeamMember)
        {
            $TeamMember->created_by = Auth::id();
			$TeamMember->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($TeamMember)
        {
            //
        });

        static::deleting(function($TeamMember)
        {
            //
		});
    }
}
