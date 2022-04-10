<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class Event extends Model
{
    protected $table = 'rti_event';
    protected $primaryKey = 'id';

	public static function boot()
    {
        parent::boot();    
    }
}
