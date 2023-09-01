<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use reference\EventEntries;

use Auth;
use Carbon;

class EntryConfigBelt extends Model
{
    protected $table = 'uq_entry_config_belt';
    protected $primaryKey = 'id';
    
    public static $rules = array(
        'entry_id' => 'required',
        'name' => 'required',
        'name_en' => 'required',
        // 'possible_belts' => 'required'
    );

    public function entry()
    {
        return $this->belongsTo(EventEntries::class, 'entry_id');
    }   

    public function belts()
    {
        return $this->belongsToMany(BeltGroup::class, 'uq_bjj_belt_group', 'entry_id', 'belt_group_id')->withPivot('possible_belts');;
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
