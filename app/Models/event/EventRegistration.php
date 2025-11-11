<?php

namespace event;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

use Auth;
use Carbon;
use Config;

class EventRegistration extends Model
{
    protected $table = 'uq_event_registration';
    
    public static function rules($id)
    {
        return [
            'member_id' => [
                'required',
                Rule::unique('uq_event_registration')->where(function ($query) use ($id) {
                    return $query->where('event_id', request()->input('event_id'))
                        ->where('entry_id', request()->input('entry_id'))
                        ->where('id', '!=', $id);
                }),
            ],
            'event_id' => 'required',
            'entry_id' => 'required',
            'entry_age_id' => 'required',
            'entry_belt_id' => 'required',
            'entry_weight_id' => 'required',
            'academy_id' => 'required',
            //'status' => 'required'
        ];
    }

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

    public function member()
    {
        return $this->belongsTo('member\Member', 'member_id');
    }

    public function team()
    {
        return $this->belongsTo('team\Team', 'team_id');
    }

    public function entry()
    {
        return $this->belongsTo('reference\EventEntries', 'entry_id');
    }

    public function age()
    {
        return $this->belongsTo('reference\EntryConfigAge', 'entry_age_id');
    }

    public function belt()
    {
        return $this->belongsTo('reference\EntryConfigBelt', 'entry_belt_id');
    }

    public function weight()
    {
        return $this->belongsTo('reference\EntryConfigWeight', 'entry_weight_id');
    }

    public function academy()
    {
        return $this->belongsTo('academy\Academy', 'academy_id');
    }

    public function country()
    {
        return $this->belongsTo('country\Country', 'country_id');
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
        return $this->hasMany('event\EventRegistrationStatus', 'event_registration_id');
    }

    public function sport()
    {
        return $this->belongsTo('sport\Sport', 'sport_id');
    }

    public function eventRefundRequest()
    {
        return $this->hasOne('event\EventRefundRequest', 'event_registration_id');
    }
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($eventRegistration)
        {
            $eventRegistration->updated_by = Auth::id();
			$eventRegistration->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($eventRegistration)
        {
            $eventRegistration->source_type = 'admin';
            $eventRegistration->created_by = Auth::id();
			$eventRegistration->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($eventRegistration)
        {
            $statusArr['status'] = @Config::get('smart.event_registration_status')['created'];
            $statusArr['changed_by'] = Auth::id();
			$statusArr['changed_at'] = Carbon\Carbon::now()->toDateTimeString();

            $eventRegistration->statuses()->create($statusArr);
        });

        static::deleting(function($member)
        {

		});
    }
}
