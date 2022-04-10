<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use reference\EntryConfigBelt;
use reference\EntryConfigAge;
use reference\EntryConfigWeight;
use reference\EventEntriesFee;

use Auth;
use Carbon;

class EventEntries extends Model
{
    protected $table = 'uq_event_entries';
    protected $primaryKey = 'id';
    
    public static function rules($id) 
    {
		return array(
            'name' => 'required',
            'name_en' => 'required',
            'gender_code' => 'required',
            'entrance_fee' => 'required',
            'event_id' => 'required'
		);
	}

    public function configBelts()
    {
        return $this->hasMany(EntryConfigBelt::class, 'entry_id');
    }

    public function configAges()
    {
        return $this->hasMany(EntryConfigAge::class, 'entry_id');
    }

    public function configWeights()
    {
        return $this->hasMany(EntryConfigWeight::class, 'entry_id');
    }

    public function configEntriesFees()
    {
        return $this->hasMany(EventEntriesFee::class, 'entry_id');
    }
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($eventEntries)
        {
            $eventEntries->updated_by = Auth::id();
			$eventEntries->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($eventEntries)
        {
            $eventEntries->created_by = Auth::id();
			$eventEntries->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($eventEntries)
        {
            //
        });

        static::deleting(function($eventEntries)
        {
		});
    }
}
