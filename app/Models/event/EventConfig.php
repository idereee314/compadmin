<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;
use DB;

class EventConfig extends Model
{
    protected $table = 'uq_event_config';
    protected $primaryKey = 'id';

    public static function rules($id)
    {
		return array(
            'event_id' => 'required|unique:uq_event_config,event_id,'.$id.',id',
            'reg_date' => 'required',
            'start_time' => 'required',
		);
	}

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

    public function registration()
    {
        return $this->hasMany('event\EventRegistration', 'event_id', 'event_id');
    }

    public function registrationTen()
    {
        return $this->hasMany('event\EventRegistration', 'event_id', 'event_id')->orderBy('uq_event_registration.created_at', 'asc')->limit(10);
    }

    public function entries()
    {
        return $this->hasMany('reference\EventEntries', 'event_id', 'event_id');
    }

    function configBelts()
    {
        return $this->hasManyThrough('reference\EntryConfigBelt', 'reference\EventEntries', 'event_id', 'entry_id', 'event_id', 'id');
    }

    function configAges()
    {
        return $this->hasManyThrough('reference\EntryConfigAge', 'reference\EventEntries', 'event_id', 'entry_id', 'event_id', 'id');
    }

    function configWeights()
    {
        return $this->hasManyThrough('reference\EntryConfigWeight', 'reference\EventEntries', 'event_id', 'entry_id', 'event_id', 'id');
    }

    public function sport()
    {
        return $this->belongsTo('sport\Sport', 'sport_id');
    }
    
	public static function boot()
    {
        parent::boot();    
    }
}
