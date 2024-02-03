<?php

namespace event;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class EventAward extends Model
{
    protected $table = 'uq_event_award';
    protected $primaryKey = 'id';
    protected $fillable = ['event_registration_id', 'member_id', 'place_number', 'created_at', 'updated_at'];

    public static function rules($id)
    {
        return [
            'place_number' => [
                'required',
                Rule::unique('uq_event_award')->where(function ($query) use ($id) {
                    return $query->where('event_registration_id', request()->input('event_registration_id'))
                        ->where('id', '!=', $id);
                }),
            ],
            'event_registration_id' => 'required',
        ];
    }

    public function eventRegistration()
    {
        return $this->belongsTo('event\EventRegistration', 'event_registration_id');
    }

    public function member()
    {
        return $this->belongsTo('member\Member', 'member_id');
    }

	public static function boot()
    {
        parent::boot();    

        static::updating(function($award)
        {
            $award->updated_by = Auth::id();
			$award->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($award)
        {
            $award->created_by = Auth::id();
			$award->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
