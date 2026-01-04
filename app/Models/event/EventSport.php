<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class EventSport extends Model
{
    protected $table = 'rti_event_sport';
    protected $primaryKey = 'event_id';

    public function sport()
    {
        return $this->belongsTo('sport\Sport', 'sport_id');
    }

	public static function boot()
    {
        parent::boot();    
    }
}
