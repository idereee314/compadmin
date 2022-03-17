<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class EntryConfigBelt extends Model
{
    protected $table = 'uq_entry_config_belt';
    
    public static function rules($id) 
    {
		return array(
            'entry_id' => 'required',
            'name' => 'required',
            'name_en' => 'required',
            'possible_belts' => 'required'
		);
	}
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($entryConBelt)
        {
            $entryConBelt->updated_by = Auth::id();
			$entryConBelt->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($entryConBelt)
        {
            $entryConBelt->created_by = Auth::id();
			$entryConBelt->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($entryConBelt)
        {
            //
        });

        static::deleting(function($entryConBelt)
        {
		});
    }
}
