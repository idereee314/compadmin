<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use reference\EntryConfigAge;
use reference\EventEntries;

use Auth;
use Carbon;

class EntryConfigWeight extends Model
{
    protected $table = 'uq_entry_config_weight';
    protected $primaryKey = 'id';

    public static $rules = array(
        'entry_id' => 'required',
        'weight' => 'required',
        'entry_age_id' => 'required'
    );

    public function age()
    {
        return $this->belongsTo(EntryConfigAge::class, 'entry_age_id');
    }

    public function entry()
    {
        return  $this->belongsTo(EventEntries::class, 'entry_id');
    }
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($entryConWeight)
        {
            $entryConWeight->updated_by = Auth::id();
			$entryConWeight->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($entryConWeight)
        {
            $entryConWeight->created_by = Auth::id();
			$entryConWeight->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($entryConWeight)
        {
            //
        });

        static::deleting(function($entryConWeight)
        {
		});
    }
}
