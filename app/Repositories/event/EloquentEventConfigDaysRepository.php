<?php

namespace event;

use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Http\Request;
use Log;

class EloquentEventConfigDaysRepository implements EventConfigDaysRepository
{
    public function find($id)
    {
        return EventDays::find($id);
    }

    public function create($input)
    {
        $eventDays = new EventDays;
        $eventDays->event_id = $input['event_id'];
        $eventDays->reg_start_date = $input['reg_start_date'];
        $eventDays->reg_end_date = $input['reg_end_date'];
        $eventDays->save();

        return $eventDays;
    }

    public function update($id, $input)
    {
        $eventDays = $this->find($id);
        $eventDays->event_id = $input['event_id'];
        $eventDays->reg_start_date = $input['reg_start_date'];
        $eventDays->reg_end_date = $input['reg_end_date'];
        $eventDays->save();

        return $eventDays;
    }

    public function delete($id)
    {
        $eventDays = $this->find($id);
        $eventDays->delete();
    }

    public function getMatByEventId($event_id)
    {
        $eventDays = new EventDays;

        return $eventDays->getMatByEventId($event_id);
    }

    public function generateMatAndDays($event_id, $mat, $start_date, $end_date)
	{
        Log::info("Generating mats and days for event_id: $event_id, mat: $mat, start_date: $start_date, end_date: $end_date");
        $currentDate = Carbon::parse($start_date);
        $endDate = Carbon::parse($end_date);
        $item_no = 1;
        while ($currentDate->lte($endDate)) {
            $eventDay = new EventDays;
            $eventDay->event_id = $event_id;
            $eventDay->item_no = $item_no;
            $eventDay->start_date = $currentDate->toDateString();
            $eventDay->save();
            $this->addMatoDays($event_id, $mat, $eventDay->id);
            $currentDate->addDay();
            $item_no++;
        }
    }

    public function resetMatAndDays($event_id)
	{
        Log::info("Resetting mats and days for event_id: $event_id");
        EventMatches::where('event_id', $event_id)->delete();
        EventMateBracket::where('event_id', $event_id)->delete();
        EventMate::where('event_id', $event_id)->delete();
        EventDays::where('event_id', $event_id)->delete();
    }

    private function addMatoDays($event_id, $mat, $day_id)
    {
        $mat = (int) $mat; // Ensure $mat is an integer

        foreach (range(1, $mat) as $singleMat) {
            $eventMate = new EventMate;
            $eventMate->event_id = $event_id;
            $eventMate->mate_no = $singleMat; // Insert each mat as a number from 1 to n
            $eventMate->day_id = $day_id;
            $eventMate->total_hour = 0;
            $eventMate->save();
        }
    }

    public function saveBracket($event_id, $day_id, $mat_id, $bracket, $total_hour)
    {
        EventMateBracket::updateOrCreate(
            [
                'event_id' => $event_id,
                'day_id' => $day_id,
                'mat_id' => $mat_id,
                'entry_id' => $bracket['entry_id'],
                'entry_belt_id' => $bracket['entry_belt_id'],
                'entry_age_id' => $bracket['entry_age_id'],
                'entry_weight_id' => $bracket['entry_weight_id'],
                'total_hour' => $total_hour,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function resetMateBracker($event_id, $day_id, $mat_id)
    {
        // Get bracket IDs before deleting so we can clean up associated matches
        $bracketIds = DB::table('uq_comp.uq_event_mate_brackets')
            ->where('event_id', $event_id)
            ->where('day_id', $day_id)
            ->where('mat_id', $mat_id)
            ->pluck('id');

        if ($bracketIds->isNotEmpty()) {
            DB::table('uq_comp.uq_event_matches')
                ->whereIn('bracket_id', $bracketIds)
                ->delete();
        }

        return DB::table('uq_comp.uq_event_mate_brackets')
            ->where('event_id', $event_id)
            ->where('day_id', $day_id)
            ->where('mat_id', $mat_id)
            ->delete();
    }

    /**
     * Get IDs of mate_brackets that have at least one completed match
     * with both participants. These brackets are "locked" and cannot
     * be moved or removed.
     */
    public function getLockedBracketIds($event_id)
    {
        return DB::table('uq_event_matches')
            ->where('event_id', $event_id)
            ->whereNotNull('reg_one_id')
            ->whereNotNull('reg_two_id')
            ->where('status', 'C')
            ->distinct()
            ->pluck('bracket_id');
    }

    /**
     * Get current bracket-to-mat assignments as a map:
     *   bracketKey => ['day_id' => ..., 'mat_id' => ..., 'bracket_id' => ...]
     */
    public function getCurrentBracketAssignments($event_id)
    {
        $brackets = DB::table('uq_event_mate_brackets')
            ->where('event_id', $event_id)
            ->select('id', 'day_id', 'mat_id', 'entry_id', 'entry_belt_id', 'entry_age_id', 'entry_weight_id')
            ->get();

        $map = [];
        foreach ($brackets as $b) {
            $key = $b->entry_id.'_'.$b->entry_belt_id.'_'.$b->entry_age_id.'_'.$b->entry_weight_id;
            $map[$key] = [
                'day_id' => $b->day_id,
                'mat_id' => $b->mat_id,
                'bracket_id' => $b->id,
            ];
        }

        return $map;
    }

    /**
     * Remove a single bracket assignment and its matches from a mat.
     */
    public function removeBracketFromMat($event_id, $day_id, $mat_id, $bracket_id)
    {
        DB::table('uq_event_matches')
            ->where('event_id', $event_id)
            ->where('bracket_id', $bracket_id)
            ->delete();

        DB::table('uq_event_mate_brackets')
            ->where('id', $bracket_id)
            ->where('event_id', $event_id)
            ->where('day_id', $day_id)
            ->where('mat_id', $mat_id)
            ->delete();
    }

    public function dictData($event_id)
    {
        $eventDays['days'] = EventDays::where('event_id', $event_id)->get();
        $mates = EventMate::select('day_id', 'event_id', 'mate_no', 'id')->where('event_id', $event_id)->get();
        $eventDays['mate'] = $mates->groupBy('day_id');

        return $eventDays;
    }

    public function getMatchByGroup(Request $request, $eventId)
    {
        if (! $eventId) {
            return [];
        }

        // Retrieve filter inputs
        $mat = (int) $request->input('mat');
        $age = (int) $request->input('age');
        $day = (int) $request->input('day');
        $gender = (int) $request->input('gender');
        $weight = (int) $request->input('weight');

        // Retrieve EventDays with related mates, matches, and brackets
        $regs = EventDays::with([
            'mates' => function ($query) use ($eventId, $mat, $age, $gender, $weight) {
                $query->select('day_id', 'event_id', 'mate_no', 'total_hour', 'id')
                    ->where('event_id', $eventId)
                    ->when($mat, function ($q) use ($mat) {
                        $q->where('id', $mat);
                    })
                    ->with([
                        'matches' => function ($subQuery) use ($eventId, $age, $gender, $weight) {
                            $subQuery->select('id', 'mat_id', 'start_time', 'end_time', 'status', 'entry_id', 'entry_belt_id', 'entry_age_id', 'entry_weight_id')
                                ->where('event_id', $eventId)
                                ->when($age, function ($q) use ($age) {
                                    $q->where('entry_age_id', $age);
                                })
                                ->when($gender, function ($q) use ($gender) {
                                    $q->whereHas('entry', function ($entryQuery) use ($gender) {
                                        $entryQuery->where('gender_code', $gender);
                                    });
                                })
                                ->when($weight, function ($q) use ($weight) {
                                    $q->where('entry_weight_id', $weight);
                                })
                                ->with([
                                    'entry:id,name,duration',
                                ]);
                        },
                    ]);
            },
        ])
            ->when($day, function ($query) use ($day) {
                $query->where('id', $day);
            })
            ->where('event_id', $eventId)
            ->selectRaw('ROW_NUMBER() OVER (ORDER BY start_date ASC) as day, item_no, *')
            ->get();

        foreach ($regs as $day) {
            foreach ($day->mates as $mate) {
                $matchCollection = [];
                foreach ($mate->matches as $match) {
                    // Dynamically load brackets for each match
                    $bracketData = [
                        'bracket_id' => $match->id,
                    ];
                    $matchCollection[] = $bracketData;
                }
                Log::info('Match Collection: ', array_column($matchCollection, 'bracket_id'));

                $bracketIds = array_column($matchCollection, 'bracket_id');

                if (empty($bracketIds)) {
                    $mate['event_matches'] = collect();

                    continue;
                }

                // Fetch all matches with bracket_id and is_double_loser for ordering logic
                $allMatches = EventMatches::select('id', 'bracket_id', 'reg_one_id', 'reg_two_id', 'reg_win_id', 'status', 'end_time', 'order_no', 'is_double_loser')
                    ->whereIn('bracket_id', $bracketIds)
                    ->where('event_id', $eventId)
                    ->orderBy('order_no', 'asc')
                    ->orderBy('id', 'asc')
                    ->with([
                        'regOne:id,member_id',
                        'regTwo:id,member_id',
                        'regOne.member:id,firstname,lastname',
                        'regTwo.member:id,firstname,lastname',
                        'regWin.member:id,firstname,lastname',
                    ])
                    ->get();

                // Group matches by bracket in assignment order
                $matchesByBracket = [];
                foreach ($bracketIds as $bracketId) {
                    $bracketMatches = $allMatches->where('bracket_id', $bracketId)->values();
                    if ($bracketMatches->isNotEmpty()) {
                        $matchesByBracket[] = $bracketMatches;
                    }
                }

                // Build ordered queue: brackets sequential, with 2-match break before medal matches
                $queue = collect();
                $totalBrackets = count($matchesByBracket);

                for ($b = 0; $b < $totalBrackets; $b++) {
                    $bracketMatches = $matchesByBracket[$b];
                    $isLast = ($b === $totalBrackets - 1);

                    // Separate regular (non-medal) and medal matches (Gold/Bronze: order_no >= 9996)
                    $regular = $bracketMatches->filter(fn ($m) => $m->order_no < 9996)->values();
                    $medal = $bracketMatches->filter(fn ($m) => $m->order_no >= 9996)
                        ->sortBy('order_no')->values();

                    // Add regular matches
                    $queue = $queue->merge($regular);

                    // Medal match break logic (skip for last bracket on the mat)
                    if ($medal->isNotEmpty() && ! $isLast) {
                        // Count losers bracket matches that provide a rest break
                        // between the last winners bracket match (SF) and medal matches
                        $lastWinnerOrderNo = $regular->filter(fn ($m) => ! $m->is_double_loser)->max('order_no') ?? 0;
                        $breakCount = $regular->filter(fn ($m) => $m->order_no > $lastWinnerOrderNo)->count();

                        if ($breakCount < 2) {
                            // Borrow first matches from the next bracket as a rest break
                            $needed = 2 - $breakCount;
                            $nextRegular = $matchesByBracket[$b + 1]
                                ->filter(fn ($m) => $m->order_no < 9996)->values();
                            $borrowed = $nextRegular->take($needed);
                            $queue = $queue->merge($borrowed);

                            // Remove borrowed matches from next bracket so they aren't added twice
                            $borrowedIds = $borrowed->pluck('id')->toArray();
                            $matchesByBracket[$b + 1] = $matchesByBracket[$b + 1]
                                ->reject(fn ($m) => in_array($m->id, $borrowedIds))->values();
                        }
                    }

                    // Add medal matches
                    $queue = $queue->merge($medal);
                }

                $mate['event_matches'] = $queue;
            }
        }

        return $regs;
    }

    public function getEventEntries($eventId)
    {
        if (! $eventId) {
            return [];
        }

        // Fetch and group EventRegistration data
        return EventRegistration::selectRaw('entry_id, entry_belt_id, entry_age_id, entry_weight_id, COUNT(*) as total')
            ->where('event_id', $eventId)
            ->where('status', Config::get('smart.event_registration_status')['approved'])
            ->groupBy('entry_id', 'entry_belt_id', 'entry_age_id', 'entry_weight_id')
            ->with([
                'entry:id,name,gender_code', // Include entry relationship
                'belt:id,name', // Include belt relationship
                'age:id,start_age,end_age', // Include age relationship
                'weight:id,weight', // Include weight relationship
            ])
            ->get();
    }
}