<?php

namespace event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMate extends Model
{
    protected $table = 'uq_event_mates';
    protected $primaryKey = 'id';

    public static function rules($id) {
		return array(
            'event_id' => 'required|unique:uq_event_config,event_id,'.$id.',id',
            'day_id' => 'required',
            'mate_no' => 'required',
            'total_hour' => 'required',
		);
	}

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

    public function day()
    {
        return $this->belongsTo('event\EventDays', 'event_id', 'day_id');
    }

    public function matches()
    {
        return $this->hasMany('event\EventMateBracket', 'mat_id');
    }

}
