<?php

namespace event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMatches extends Model
{
    protected $table = 'uq_event_matches';
    protected $primaryKey = 'id';
  
    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

    public function entry()
    {
        return $this->belongsTo('reference\EventEntries', 'entry_id');
    }

    public function age()
    {
        return $this->belongsTo('reference\EntryConfigAge', 'entry_age_id');
    }

    public function belt()
    {
        return $this->belongsTo('reference\EntryConfigBelt', 'entry_belt_id');
    }

    public function weight()
    {
        return $this->belongsTo('reference\EntryConfigWeight', 'entry_weight_id');
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
        return $this->belongsTo('event\EventMateBracket', 'entry_id', 'entry_id')
            ->whereColumn('entry_belt_id', 'uq_event_mate_brackets.entry_belt_id')
            ->whereColumn('entry_age_id', 'uq_event_mate_brackets.entry_age_id')
            ->whereColumn('entry_weight_id', 'uq_event_mate_brackets.entry_weight_id');
    }

}
