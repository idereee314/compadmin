<?php

namespace event;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;
use Config;

class EventBrackets extends Model
{
    protected $table = 'uq_event_brackets';
  
    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
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
    
    public function regOne()
    {
        return $this->belongsTo('event\EventRegistration', 'reg_one_id', 'id');
    }

    public function regTwo()
    {
        return $this->belongsTo('event\EventRegistration', 'reg_two_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function($eventBrackets)
        {
			$eventBrackets->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($eventBrackets)
        {
			$eventBrackets->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
