<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class EventPayment extends Model
{
    protected $table = 'uq_event_payment';
    protected $primaryKey = 'id';

    public function eventRegistration()
    {
        return $this->belongsTo('event\EventRegistration', 'registration_id');
    }

	public static function boot()
    {
        parent::boot();    
    }
}
