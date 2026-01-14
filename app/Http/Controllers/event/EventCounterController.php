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


class EventCounterController extends Controller
{
    public $restful = true;

    public function __construct(ConfigDays $configDays, Event $event, Mathes $mathes, EventConfig $eventConfig)
    {
        $this->view_path = 'event.config.counter';
        $this->configDays = $configDays;
        $this->event = $event;
        $this->mathes = $mathes;
        $this->eventConfig = $eventConfig;
    }

    public function show($match_id)
    {
        $data['view_path'] = $this->view_path;
        $data['matchId'] = $match_id;
        $matchData = $this->mathes->getMatchesByEventId($match_id);
        $data['registered'] = $matchData['registrations'];
        $data['bracket'] = $matchData['bracket'];
        return view($this->view_path.'.index', $data);
    }

    public function edit_status_show($match_id)
    {
        $data['view_path'] = 'event.config.match';
        $data['matchId'] = $match_id;
        $matchData = $this->mathes->getMatchesByEventId($match_id);
        $data['registered'] = $matchData['registrations'];
        $data['bracket'] = $matchData['bracket'];
        return view($this->view_path.'.index', $data);
    }

    public function set_winner(Request $request, $match_id)
    {
        $this->mathes->updateWinner($match_id, $request);
        return $this->edit_status_show($match_id);
    }

    public function edit_winner(Request $request, $match_id)
    {
        $this->mathes->editWinner($match_id, $request);
        return $this->edit_status_show($match_id);
    }

    public function set_double_loser(Request $request, $match_id)
    {
        $this->mathes->setDoubleLoser($match_id, $request);
        $match = $this->mathes->find($match_id);
        return $this->edit_status_show($match_id);
    }

    public function getPrevMatch(Request $request, $match_id)
    {
        $prev_id =  $this->mathes->getPrevMatches($match_id, $request);
        if(empty($prev_id)) {
            $prev_id = $match_id;
        }
        return $this->show($prev_id);
    }

    public function getNextMatch(Request $request, $match_id)
    {
        $next_id =  $this->mathes->getNextMatches($match_id, $request);
        if(empty($next_id)) {
            $next_id = $match_id;
        }
        return $this->show($next_id);
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
