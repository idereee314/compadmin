<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class EventEntriesFee extends Model
{
    protected $table = 'uq_event_entries_fee';
    protected $primaryKey = 'id';
    
    public static function rules($id) 
    {
		return array(
            'entry_id' => 'required',
            'end_date' => 'required',
            'entrance_fee' => 'required'
		);
	}
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($eventEntryFee)
        {
            $eventEntryFee->updated_by = Auth::id();
			$eventEntryFee->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($eventEntryFee)
        {
            $eventEntryFee->created_by = Auth::id();
			$eventEntryFee->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($eventEntryFee)
        {
            //
        });

        static::deleting(function($eventEntryFee)
        {
		});
    }
}
