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
use Config;

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

    public function eventTeamRegistration()
    {
        return $this->hasMany(EventTeamRegistration::class, 'team_id');
    }

    public function statuses()
    {
        return $this->hasMany('event\EventTeamRegistrationMemberStatus', 'team_registration_member_id');
    }

    public function team()
    {
        return $this->belongsTo('team\Team', 'team_id');
    }


    public static function boot()
    {
        parent::boot();

        static::updating(function($teamMember)
        {
            $teamMember->updated_by = Auth::id();
			$teamMember->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($teamMember)
        {
            $teamMember->created_by = Auth::id();
			$teamMember->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($teamMember) {
            $statusArr['status'] = @Config::get('smart.event_registration_status')['created'];
            $statusArr['changed_by'] = Auth::id();
            $statusArr['changed_at'] = Carbon\Carbon::now()->toDateTimeString();

            // $teamMembers = $statusArr->eventTeamRegistration;
            // // dd($teamMembers);
            // foreach ($teamMembers as $teamMember) {
            //     $teamMember->statuses()->create($statusArr);
            // }

            // dd($teamMember->statuses);

            // dd($teamMember);

            //  $teamMember->statuses()->create($statusArr);
        });

        static::deleting(function($teamMember)
        {
            //
		});
    }
}
