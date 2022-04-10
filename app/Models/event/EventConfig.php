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
            'reg_start_date' => 'required',
            'reg_end_date' => 'required'
		);
	}

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

	public static function boot()
    {
        parent::boot();    
    }
}
