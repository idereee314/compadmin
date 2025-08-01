<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;


class EventDays extends Model
{
    protected $table = 'uq_event_days';
    protected $primaryKey = 'id';

    public static function rules($id) {
		return array(
            'event_id' => 'required|unique:uq_event_config,event_id,'.$id.',id',
            'start_date' => 'required',
		);
	}

    public function getMatByEventId($event_id)
    {

        $script = DB::table('uq_event_days')
        ->select('uq_event_days.id as day_id')
        ->where('uq_event_days.event_id', $event_id)
        ->orderBy('uq_event_days.start_date', 'asc')
        ->selectRaw('ROW_NUMBER() OVER (ORDER BY uq_event_days.start_date ASC) as day');

        // Fetch all days for the given event_id
        $days = $script->get();

        $result = [];

        foreach ($days as $day) {
            // Fetch all mats for the current day with event_id filter
            $mats = DB::table('uq_event_mates')
                ->select('id as mat_id', 'mate_no', 'total_hour')
                ->where('day_id', $day->day_id)
                ->where('event_id', $event_id)
                ->get();

            $matsWithBrackets = [];

            foreach ($mats as $mat) {
                // Fetch brackets for the current mat
                $brackets = DB::table('uq_event_mate_brackets')
                    ->select('entry_id','entry_belt_id','entry_age_id','entry_weight_id')
                    ->where('mat_id', $mat->mat_id)
                    ->where('day_id', $day->day_id)
                    ->where('event_id', $event_id)
                    ->get();
                $bracketsArray = json_decode(json_encode($brackets), true);

                foreach ($bracketsArray as &$ba) {
                    $match = DB::table('uq_event_matches')
                        ->select('id')
                        ->where('event_id', $event_id)
                        ->where('entry_id', $ba['entry_id'])
                        ->where('entry_belt_id', $ba['entry_belt_id'])
                        ->where('entry_age_id', $ba['entry_age_id'])
                        ->where('entry_weight_id', $ba['entry_weight_id'])
                        ->whereNotNull('reg_one_id')
                        ->whereNotNull('reg_two_id')
                        ->where('status', '=', 'C')
                        ->first();
                    $ba['is_complete'] = !empty($match);
                }

                // Build the mat structure
                $matsWithBrackets[] = [
                    'mat' => $mat->mate_no,
                    'mat_id' => $mat->mat_id,
                    'brackets' => $bracketsArray,
                ];
            }

            // Build the structure for the day
            $result[] = [
                'day' => $day->day,
                'day_id' => $day->day_id,
                'mats' => $matsWithBrackets,
            ];
        }

        return $result;
    }

    public function getMatchesByEventId($event_id)
    {
        return DB::table('uq_event_matches')
            ->select('uq_event_matches.id as match_id', 'uq_event_matches.match_no', 'uq_event_matches.start_time', 'uq_event_matches.end_time')
            ->where('uq_event_matches.event_id', $event_id)
            ->orderBy('uq_event_matches.start_time', 'asc')
            ->get();
    }

    public function event()
    {
        return $this->belongsTo('event\Event', 'event_id');
    }

    public function mates()
    {
        return $this->hasMany('event\EventMate', 'day_id');
    }
}
