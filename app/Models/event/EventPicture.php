<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class EventPicture extends Model
{
    protected $table = 'rti_event_picture';
    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();
    }
}
