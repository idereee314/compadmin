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

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

	public static function boot()
    {
        parent::boot();    
    }
}
