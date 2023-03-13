<?php

namespace event;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;
use Config;

class EventTeamRegistration extends Model
{
    protected $table = 'uq_team_registration';
    
    public static function rules($id) 
    {
		return array(
            'event_id' => 'required',
            'team_id' => 'required',
            'entry_id' => 'required',
            'academy_id' => 'required',
           
		);
	}

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

    public function member()
    {
        return $this->belongsTo('member\Member', 'member_id');
    }

    public function teamathlete()
    {
        return $this->hasMany('member\TeamMember', 'team_id', 'team_id');
    }

    public function entry()
    {
        return $this->belongsTo('reference\EventEntries', 'entry_id');
    }

    public function age()
    {
        return $this->belongsTo('reference\EntryConfigAge', 'entry_age_id');
    }

    public function academy()
    {
        return $this->belongsTo('academy\Academy', 'academy_id');
    }

    public function team()
    {
        return $this->belongsTo('team\Team', 'team_id');
    }

    public function award()
    {
        return $this->hasOne('event\EventAward', 'event_registration_id');
    }

    public function payments()
    {
        return $this->hasMany('event\EventPayment', 'registration_id');
    }

    public function payment()
    {
        return $this->hasOne('event\EventPayment', 'registration_id', 'id');
    }

    public function statuses()
    {
        return $this->hasMany('event\EventTeamRegistrationStatus', 'team_registration_id');
    }
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($EventTeamRegistration)
        {
            $EventTeamRegistration->updated_by = Auth::id();
			$EventTeamRegistration->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($EventTeamRegistration)
        {
            $EventTeamRegistration->source_type = 'admin';
            $EventTeamRegistration->created_by = Auth::id();
			$EventTeamRegistration->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($EventTeamRegistration)
        {
            $statusArr['status'] = @Config::get('smart.event_registration_status')['created'];
            $statusArr['changed_by'] = Auth::id();
			$statusArr['changed_at'] = Carbon\Carbon::now()->toDateTimeString();
           
            $EventTeamRegistration->statuses()->create($statusArr);
        });

        static::deleting(function($teamMember)
        {

		});
    }
}
