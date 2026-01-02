<?php

namespace event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMatches extends Model
{
    protected $table = 'uq_event_matches';
    protected $primaryKey = 'id';

    public static function rules($id) {
		return array(
            'bracket_id' => 'required|unique:uq_event_mate_brackets,event_id,'.$id.',id',
		);
	}
  
    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }
    
    public function regOne()
    {
        return $this->getMatchType('reg_one_id');
    }

    public function regTwo()
    {
        return $this->getMatchType('reg_two_id');
    }

    public function regWin()
    {
        return $this->getMatchType('reg_win_id');
    }

    private function getMatchType($col)
    {
        return $this->belongsTo('event\EventRegistration', $col, 'id');
    }
    
    public function bracket()
    {
        return $this->belongsTo('event\EventMateBracket', 'bracket_id');
    }

}
