<?php

namespace event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Input;
use Validator;

use event\EloquentEventConfigDaysRepository as ConfigDays;
use event\EloquentEventMatchesRespository as Mathes;
use event\EventConfigRepository as EventConfig;

use \Auth as Auth;
use Config;
use \HTML;
use Image;
use Log;


class EventMatchController extends Controller
{
    public $restful = true;

    public function __construct(ConfigDays $configDays, Event $event, Mathes $mathes, EventConfig $eventConfig)
    {
        $this->view_path = 'event.config.match';
        $this->configDays = $configDays;
        $this->event = $event;
        $this->mathes = $mathes;
        $this->eventConfig = $eventConfig;
    }

    public function show($eventId)
    {
        $data['view_path'] = $this->view_path;
        $data['eventId'] = $eventId;
        return view($this->view_path.'.index', $data);
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
        $this->mathes->updateWinner($match_id, $request);
        return  array(
            'status' => 'success',
            'msg' => trans('messages.success_save')
        );
    }

    public function store(Request $request, $event_id){
        $input = Input::all();

        if (false)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
            );
        }
        else
        {
            try
            {
                $this->configDays->resetMatAndDays($event_id);
                $this->configDays->generateMatAndDays($event_id, (int) $input['mate_number'], $input['start_date'], $input['end_date']);
                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_save')
                );

            }
            catch(\Illuminate\Database\QueryException $e)
            {
                $response = array(
                    'status' => 'error',
                    'msg' => trans('messages.error_save'),
                    'errors' => $e->getMessage()
                );

            }
        }
        
        return $response;
    }

    public function saveEventMateBracket(Request $request, $event_id)
    {
        try {
            $data = $request->validate([
                'schedule' => 'required|array',
                'schedule.*.day' => 'required|integer', // Validate day
                'schedule.*.mats' => 'required|array',
                'schedule.*.mats.*.mat' => 'required|integer', // Validate mat
                'schedule.*.mats.*.brackets' => 'array', // Allow empty brackets arrays
                'schedule.*.mats.*.brackets.entry_id.*' => 'integer', // Validate each bracket ID if present
                'schedule.*.mats.*.brackets.entry_belt_id.*' => 'integer', // Validate each bracket ID if present
                'schedule.*.mats.*.brackets.entry_age_id.*' => 'integer', // Validate each bracket ID if present
                'schedule.*.mats.*.brackets.entry_weight_id.*' => 'integer', // Validate each bracket ID if present
            ]);
    

            foreach ($data['schedule'] as $day) {
                foreach ($day['mats'] as $mat) {
                    $this->configDays->resetMateBracker($event_id, $day['day'], $mat['mat']);
                    if (isset($mat['brackets']) && !empty($mat['brackets'])) {
                        foreach ($mat['brackets'] as $bracket) {
                            $this->configDays->saveBracket($event_id, $day['day'], $mat['mat'], $bracket, 0);
                            $this->mathes->generateMatches($event_id,  $bracket, $day['day'], $mat['mat']);
                        }
                    }
                }
            }

        } catch (Exception $e) {
            Log:error('Error saving brackets: ' . $e->getMessage());
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
}
