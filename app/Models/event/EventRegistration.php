<?php

namespace event;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class EventRegistration extends Model
{
    protected $table = 'uq_event_registration';
    
    public static function rules($id) 
    {
		return array(
            'member_id' => 'required|unique_with:uq_event_registration,event_id,entry_id,'.$id.'=id',
            'event_id' => 'required',
            'entry_id' => 'required',
            'entry_age_id' => 'required',
            'entry_belt_id' => 'required',
            'entry_weight_id' => 'required',
            'academy_id' => 'required',
            'status' => 'required'
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

    public function award()
    {
        return $this->hasOne('event\EventAward', 'event_registration_id');
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
            //
        });

        static::deleting(function($member)
        {

		});
    }
}
