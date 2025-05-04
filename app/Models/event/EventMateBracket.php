<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;
use DB;

use Log;
class EventMateBracket extends Model
{
    protected $table = 'uq_event_mate_brackets';
    protected $fillable = [
        'event_id',
        'day_id',
        'mat_id',
        'entry_id',
        'entry_belt_id',
        'entry_age_id',
        'entry_weight_id',
        'start_time',
        'end_time',
        'total_hour',
    ];
    protected $primaryKey = 'id';

    public static function rules($id) {
		return array(
            'event_id' => 'required|unique:uq_event_config,event_id,'.$id.',id',
            'day_id' => 'required',
            'mate_no' => 'required',
            'entry_id' => 'required',
            'entry_belt_id' => 'required',
            'entry_age_id' => 'required',
            'entry_weight_id' => 'required',
            'total_hour' => 'required',
		);
	}

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

    public function entry()
    {
        return $this->belongsTo('reference\EventEntries', 'entry_id');
    }

    public function mate()
    {
        return $this->belongsTo('event\EventMate', 'mat_id');
    }

    public function days()
    {
        return $this->belongsTo('event\EventDays', 'day_id');
    }

    public function brackets()
    {
        Log::info('Entry ID:', ['entry_id' => $this->entry_id]);
        Log::info('Entry Belt ID:', ['entry_belt_id' => $this->entry_belt_id]);
        Log::info('Entry Age ID:', ['entry_age_id' => $this->entry_age_id]);
        return $this->hasMany('event\EventMatches', 'entry_id', 'entry_id')
            ->whereColumn('entry_belt_id', 'entry_belt_id')
            ->whereColumn('entry_age_id', 'entry_age_id')
            ->whereColumn('entry_weight_id', 'entry_weight_id');
    }
}
