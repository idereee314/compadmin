<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class EventCategory extends Model
{
    protected $table = 'uq_event_category';
    protected $primaryKey = 'id';

    public function config()
    {
        return $this->hasOne('event\EventConfig', 'event_id');
    }

	public static function boot()
    {
        parent::boot();    
    }
}
