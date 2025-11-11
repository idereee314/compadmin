<?php

namespace location\reference;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class EntryType extends Model
{
    protected $table = 'rtc_entry_type';
    protected $primaryKey = 'code';

	public static function boot()
    {
        parent::boot();    

        static::updating(function($entryType)
        {
            $entryType->updated_by = Auth::id();
			$entryType->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($entryType)
        {
            $entryType->created_by = Auth::id();
			$entryType->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
    
}
