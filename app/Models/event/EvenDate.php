<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class EventDate extends Model
{
    protected $table = 'rti_event_date';
    protected $primaryKey = 'id';

    protected $fillable = ['event_id', 'start_date', 'end_date'];

    public static $rules = array(
        'event_id' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
    );

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

    public static function boot()
    {
        parent::boot();
    
        // cause a delete of a product to cascade to children so they are also deleted
        static::creating(function($date)
        {
            $date->created_at = Carbon\Carbon::now()->toDateTimeString();
            $date->created_by = Auth::user()->user_id;
        });

        static::updating(function($date)
        {
            $date->updated_at = Carbon\Carbon::now()->toDateTimeString();
            $date->updated_by = Auth::user()->user_id;
        });

        static::deleting(function($date)
        {

        });
    }
}
