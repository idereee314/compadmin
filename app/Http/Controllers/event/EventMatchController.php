<?php

namespace event;

use App\Http\Controllers\Controller;
use event\EloquentEventConfigDaysRepository as ConfigDays;
use event\EloquentEventMatchesRespository as Mathes;
use event\EventConfigDaysRepository as EventDays;
use event\EventConfigRepository as EventConfig;
use reference\EventEntriesRepository as EventEntries;
use event\EventRepository as Event;
use Illuminate\Http\Request;
use Input;

class EventMatchController extends Controller
{
    public $restful = true;

    public function __construct(ConfigDays $configDays, Event $event, Mathes $mathes, EventConfig $eventConfig, EventDays $eventDays, EventEntries $eventEntries)
    {
        $this->view_path = 'event.config.match';
        $this->configDays = $configDays;
        $this->event = $event;
        $this->mathes = $mathes;
        $this->eventConfig = $eventConfig;
        $this->eventDays = $eventDays;
        $this->eventEntries = $eventEntries;
    }

    public function show($eventId)
    {
        $data['view_path'] = $this->view_path;
        $data['eventId'] = $eventId;

        return view($this->view_path.'.index', $data);
    }

    public function showSchedule($eventId)
    {
        $eventConfig = $this->eventConfig->find($eventId);
        $data['view_path'] = 'schedule';
        $data['eventConfig'] = $eventConfig;
        $data['configViewDict'] = $this->eventDays->dictData($eventConfig->event_id);
        $data['disableEdit'] = true;

        return view('schedule.index', $data);
    }

    public function edit_status_show($match_id)
    {
        $data['view_path'] = $this->view_path;
        $data['matchId'] = $match_id;
        $matchData = $this->mathes->getMatchesByEventId($match_id);
        $data['registered'] = $matchData['registrations'];

        return view($this->view_path.'.edit', $data);
    }

    public function edit_winner(Request $request, $match_id)
    {
        $result = $this->mathes->updateWinner($match_id, $request);
        
        return response()->json([
            'status' => 'success',
            'msg' => trans('messages.success_update'),
            'bye_match_ids' => $result->bye_match_ids ?? [],
        ]);
    }

    public function store(Request $request, $event_id)
    {
        $input = Input::all();

        if (false) {
            $response = [
                'status' => 'error',
                'msg' => trans('messages.error_save'),
            ];
        } else {
            try {
                $this->configDays->resetMatAndDays($event_id);
                $this->configDays->generateMatAndDays($event_id, (int) $input['mate_number'], $input['start_date'], $input['end_date']);
                $response = [
                    'status' => 'success',
                    'msg' => trans('messages.success_save'),
                ];

            } catch (\Illuminate\Database\QueryException $e) {
                $response = [
                    'status' => 'error',
                    'msg' => trans('messages.error_save'),
                    'errors' => $e->getMessage(),
                ];

            }
        }

        return $response;
    }

    public function saveEventMateBracket(Request $request, $event_id)
    {
        try {
            $data = $request->validate([
                'schedule' => 'required|array',
                'schedule.*.day' => 'required|integer',
                'schedule.*.mats' => 'required|array',
                'schedule.*.mats.*.mat' => 'required|integer',
                'schedule.*.mats.*.brackets' => 'array',
                'schedule.*.mats.*.brackets.*.entry_id' => 'required|integer',
                'schedule.*.mats.*.brackets.*.entry_belt_id' => 'required|integer',
                'schedule.*.mats.*.brackets.*.entry_age_id' => 'required|integer',
                'schedule.*.mats.*.brackets.*.entry_weight_id' => 'required|integer',
            ]);
    
            // 1. Build a set of all bracket keys that have started matches
            //    (at least one match completed). These are locked and cannot
            //    be moved, removed, or have their matches regenerated.
            $lockedBracketIds = $this->configDays->getLockedBracketIds($event_id);

            // 2. Build map of desired state: bracketKey => {day, mat}
            $desiredState = [];
            foreach ($data['schedule'] as $day) {
                foreach ($day['mats'] as $mat) {
                    if (! empty($mat['brackets'])) {
                        foreach ($mat['brackets'] as $bracket) {
                            $bKey = $bracket['entry_id'].'_'.$bracket['entry_belt_id'].'_'.$bracket['entry_age_id'].'_'.$bracket['entry_weight_id'];
                            $desiredState[$bKey] = [
                                'day' => $day['day'],
                                'mat' => $mat['mat'],
                                'bracket' => $bracket,
                            ];
                        }
                    }
                }
            }

            // 3. Build map of current state: bracketKey => {day_id, mat_id, bracket_id}
            $currentState = $this->configDays->getCurrentBracketAssignments($event_id);

            // 4. Determine which brackets to remove, add, or keep
            $currentKeys = array_keys($currentState);
            $desiredKeys = array_keys($desiredState);

            $toRemove = array_diff($currentKeys, $desiredKeys);
            $toAdd = array_diff($desiredKeys, $currentKeys);
            $toCheck = array_intersect($currentKeys, $desiredKeys);

            // 5. Remove brackets that are no longer assigned to any mat
            //    (only if not locked / no started matches)
            foreach ($toRemove as $bKey) {
                $cur = $currentState[$bKey];
                if (in_array($cur['bracket_id'], $lockedBracketIds->toArray())) {
                    // Locked bracket cannot be removed — skip silently
                    // (frontend should prevent this, but guard server-side)
                    continue;
                }
                $this->configDays->removeBracketFromMat($event_id, $cur['day_id'], $cur['mat_id'], $cur['bracket_id']);
            }

            // 6. Handle brackets that moved between mats
            foreach ($toCheck as $bKey) {
                $cur = $currentState[$bKey];
                $des = $desiredState[$bKey];
                $sameMat = ($cur['day_id'] == $des['day'] && $cur['mat_id'] == $des['mat']);

                if ($sameMat) {
                    // Bracket stays on same mat — no action needed,
                    // matches are preserved as-is
                    continue;
                }

                // Bracket is being moved to a different mat
                if (in_array($cur['bracket_id'], $lockedBracketIds->toArray())) {
                    // Locked bracket cannot be moved — skip silently
                    continue;
                }

                // Remove from old mat (deletes matches + bracket assignment)
                $this->configDays->removeBracketFromMat($event_id, $cur['day_id'], $cur['mat_id'], $cur['bracket_id']);

                // Add to new mat (creates bracket assignment + generates matches)
                $this->configDays->saveBracket($event_id, $des['day'], $des['mat'], $des['bracket'], 0);
                $this->mathes->generateMatches($event_id, $des['bracket'], $des['day'], $des['mat']);
            }

            // 7. Add newly assigned brackets (from pool or unassigned)
            foreach ($toAdd as $bKey) {
                $des = $desiredState[$bKey];
                $this->configDays->saveBracket($event_id, $des['day'], $des['mat'], $des['bracket'], 0);
                $this->mathes->generateMatches($event_id, $des['bracket'], $des['day'], $des['mat']);
            }

        } catch (\Throwable $e) {
            \Log::error('Error saving brackets: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => basename($e->getFile()).':'.$e->getLine(),
            ], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Brackets saved successfully.']);
    }

    public function getMatchesFromEventDays(Request $request, $event_id)
    {
        $input = Input::all();
        $eventDays = $this->configDays->getMatByEventId($event_id);

        return response()->json($eventDays);
    }

    public function getEntryList(Request $request, $event_id)
    {
        $input = Input::all();
        $eventDays = $this->configDays->getMatByEventId($event_id);
        
        return response()->json($eventDays);
    }

    public function getMatchByGroup(Request $request, $eventId)
    {
        return $this->configDays->getMatchByGroup($request, $eventId);
    }


    public function publicSchedule($eventId)
    {
        $event = $this->event->find($eventId);
        if (! $event) {
            abort(404);
        }

        $eventConfig = $this->eventConfig->findByEvent($eventId);
        if (! $eventConfig) {
            abort(404);
        }

        $data['event'] = $event;
        $data['eventConfig'] = $eventConfig;
        $data['configViewDict'] = $this->eventDays->dictData($eventId);
        $data['disableEdit'] = true;

        return view('event.schedule.index', $data);
    }

    public function publicGetMatchByGroup(Request $request, $eventId)
    {
        return $this->configDays->getMatchByGroup($request, $eventId);
    }

    public function scheduleManager($eventId)
    {
        $input = Input::all();
        
        $eventConfig = $this->eventConfig->findByEvent($eventId);
        
        $entries = $this->eventEntries->getEntryByEventId($eventId);

        $data['event'] = $this->event->find($eventId);
        $data['eventConfig'] = $eventConfig;
        $data['days'] = $this->eventDays->getDaysByEventId($eventId);
        $data['entries'] = $entries;
        $data['configViewDict'] = $this->eventDays->dictData($eventId);
        $data['view_path'] = $this->view_path;

        return view('event.schedule.schedule_manager', $data);
    }
}