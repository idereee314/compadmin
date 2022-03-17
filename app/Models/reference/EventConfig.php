<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class EventConfig extends Model
{
    protected $table = 'uq_event_config';
    
    public static function rules($id) 
    {
		return array(
            'event_id' => 'required',
            'reg_start_date' => 'required',
            'reg_end_date' => 'required'
		);
	}
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($eventConfig)
        {
            $eventConfig->updated_by = Auth::id();
			$eventConfig->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($eventConfig)
        {
            $eventConfig->created_by = Auth::id();
			$eventConfig->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($eventConfig)
        {
            //
        });

        static::deleting(function($eventConfig)
        {
		});
    }
}
