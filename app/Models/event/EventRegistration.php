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
            'member_id' => 'required',
            'event_id' => 'required',
            'entry_id' => 'required',
            'entry_age_id' => 'required',
            'entry_belt_id' => 'required',
            'entry_weight_id' => 'required',
            'academy_id' => 'required',
            'source_type' => 'required'
		);
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
