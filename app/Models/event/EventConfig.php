<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class EventConfig extends Model
{
    protected $table = 'uq_event_config';
    protected $primaryKey = 'id';

    public static function rules($id) 
    {
		return array(
            'event_id' => 'required|unique:uq_event_config,event_id,'.$id.',id',
            'reg_date' => 'required',
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

	public static function boot()
    {
        parent::boot();    
    }
}
