<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class EventToplistPoint extends Model
{
    protected $table = 'uq_event_toplist_point';
    protected $primaryKey = 'id';
    protected $fillable = ['event_id'];
    
    public static function rules($id) 
    {
		return array(
            'start_pos' => 'required|numeric',
            'end_pos' => 'nullable|numeric'
		);
	}

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($EventToplistPoint)
        {
            $EventToplistPoint->updated_by = Auth::id();
			$EventToplistPoint->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($EventToplistPoint)
        {
            $EventToplistPoint->created_by = Auth::id();
			$EventToplistPoint->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($EventToplistPoint)
        {
            //
        });

        static::deleting(function($EventToplistPoint)
        {
		});
    }
}
