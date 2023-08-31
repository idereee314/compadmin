<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use reference\EventEntries;

use Auth;
use Carbon;

class BeltGroup extends Model
{
    protected $table = 'uq_bjj_belt_group';
    protected $primaryKey = 'id';
    
    public function configBelts()
    {
        return $this->hasMany('reference\EntryConfigBelt', 'possible_belts');
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
