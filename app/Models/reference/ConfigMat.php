<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use reference\EventEntries;

use Auth;
use Carbon;

class ConfigMat extends Model
{
    protected $table = 'uq_config_mat';
    protected $primaryKey = 'id';

    public static function rules($id) 
    {
		return array(
            'name' => 'required',
		);
	}

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($configMat)
        {
            $configMat->updated_by = Auth::id();
			$configMat->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($configMat)
        {
            $configMat->created_by = Auth::id();
			$configMat->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($configMat)
        {
            //
        });

        static::deleting(function($configMat)
        {
		});
    }
}
