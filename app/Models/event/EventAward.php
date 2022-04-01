<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class EventAward extends Model
{
    protected $table = 'uq_event_award';
    protected $primaryKey = 'id';

    public static function rules($id) 
    {
		return array(
            'place_number' => 'required|unique_with:uq_event_award,event_registration_id,'.$id.'=id',
            'event_registration_id' => 'required'
		);
	}

    public function eventRegistration()
    {
        return $this->belongsTo('event\EventRegistration', 'event_registration_id');
    }

    public function member()
    {
        return $this->belongsTo('member\Member', 'member_id');
    }

	public static function boot()
    {
        parent::boot();    

    }
}
