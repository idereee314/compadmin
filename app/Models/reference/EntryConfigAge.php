<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class EntryConfigAge extends Model
{
    protected $table = 'uq_entry_config_age';
    
    public static function rules($id) 
    {
		return array(
            'entry_id' => 'required',
            'start_age' => 'required',
            'end_age' => 'required',
            'possible_ages' => 'required'
		);
	}
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($entryConAge)
        {
            $entryConAge->updated_by = Auth::id();
			$entryConAge->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($entryConAge)
        {
            $entryConAge->created_by = Auth::id();
			$entryConAge->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($entryConAge)
        {
            //
        });

        static::deleting(function($entryConAge)
        {
		});
    }
}
