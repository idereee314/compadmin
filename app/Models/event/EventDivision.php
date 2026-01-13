<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class EventDivision extends Model
{
    protected $table = 'uq_event_division';
    protected $primaryKey = 'id';
    
    function configBelt()
    {
        return $this->belongsTo('reference\EntryConfigBelt', 'belt_id')->orderBy('name', 'desc');
    }

    function configWeight()
    {
        return $this->belongsTo('reference\EntryConfigWeight', 'weight_id');
    }

	public static function boot()
    {
        parent::boot();   
        
        static::updating(function($division)
        {
            $division->updated_at = Carbon\Carbon::now()->toDateTimeString();
            $division->updated_by = Auth::user()->user_id;
        });

        static::creating(function($division)
        {
            $division->created_by = Auth::user()->user_id;
			$division->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
