<?php

namespace event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Validator;

//Repositories
use event\EventConfigRepository as EventConfig;
use event\EventRepository as Event;
use academy\AcademyRepository as Academy;
use reference\EventEntriesRepository as EventEntries;
use reference\EntryConfigBeltRepository as EntryConfigBelt;
use reference\EntryConfigAgeRepository as EntryConfigAge;
use reference\EntryConfigWeightRepository as EntryConfigWeight;
use reference\EventEntriesFeeRepository as EventEntriesFee;

//Models
use event\EventConfig as EventConfigModel;

use \Auth as Auth;
use Config;

use Image;

class EventConfigController extends Controller
{
    public $restful = true;

    public function __construct(EventConfig $eventConfig, Event $event, EventEntries $eventEntries, EntryConfigBelt $entryConfigBelt, EntryConfigAge $entryConfigAge, EntryConfigWeight $entryConfigWeight, EventEntriesFee $eventEntriesFee)
    {
        $this->view_path = 'event.config';
        $this->eventConfig = $eventConfig;
        $this->event = $event;
        $this->eventEntries = $eventEntries;
        $this->entryConfigBelt = $entryConfigBelt;
        $this->entryConfigAge = $entryConfigAge;
        $this->entryConfigWeight = $entryConfigWeight;
        $this->eventEntriesFee = $eventEntriesFee;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::all();
        $validator = Validator::make($input, EventConfigModel::rules(0));

        if ($validator->fails())
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        }
        else
        {
            try
            {
                $event = $this->eventConfig->create($input);
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
        $data['response'] = $response;
        return view('core.alert.messages', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $eventConfig = $this->eventConfig->find($id);
        // $data['eventConfig'] = $eventConfig;
        // return view($this->view_path.'.edit', $data);
        //$input = Input::all();

        $data['tabs'] = collect(Config::get("enums.event_config"))->sortBy('order')->toArray();
        $data['event_config_id'] = $id;
        $data['tab_id'] = 'tab1-1';

        $eventConfig = $this->eventConfig->find($id);
        $data['eventConfig'] = $eventConfig;
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = Input::all();

        $validator = Validator::make($input, EventConfigModel::rules($id));

        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        } else {
			try {
                $event = $this->eventConfig->update($id, $input);
            
				$response = array(
					'status' => 'success',
					'msg' => trans('messages.success_update')
				);
			}
			catch(Exception $e)
			{
				$response = array(
					'status' => 'error',
					'msg' => trans('messages.error_save'),
					'errors' => $e->getMessage()
				);
			}
		}

        $data['response'] = $response;
        return view('core.alert.messages', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
        
            $this->eventConfig->delete($id);

            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_delete')
            );
    
        } catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_delete'),
                'errors' => $e
            );
        }

        $data['response'] = $response;
        return view('core.alert.messages', $data);
    }

    public function getDatatableList(Request $request)
    {
        return $this->eventConfig->getDatatableList($request);
    }
    
    public function searchEvent()
    {
        $input = Input::all();

        $events = $this->event->searchEvent(@$input['q']);
        return json_encode($events);
    }

    public function configCopy($eventConfigId)
    {
        $data['view_path'] = $this->view_path;
        $data['eventConfigId'] = $eventConfigId;

        return view($this->view_path.'.copy_config', $data);
    }

    public function configCopyExecute($eventConfigId)
    {
        $input = Input::all();
        $validator = Validator::make($input, EventConfigModel::rules($eventConfigId));
        
        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        } else {
            try {
                $eventConfig = $this->eventConfig->find($eventConfigId);
                $eventConfigCopy = $this->eventConfig->copyEventConfig($eventConfigId, $input);
            
                $eventEntries = $this->eventEntries->getEntryByEventId($eventConfig->event_id);
                foreach($eventEntries as $eventEntry)
                {                  
                    $eventEntryCopy = $eventEntry->replicate();
                    $eventEntryCopy->event_id = $eventConfigCopy->event_id;
                    $eventEntryCopy->save();

                    foreach($eventEntry->configBelts as $belt)
                    {
                        $beltCopy = $belt->replicate();
                        $beltCopy->entry_id = $eventEntryCopy->id;
                        $beltCopy->save();
                    }

                    foreach($eventEntry->configAges as $age)
                    {
                        $ageCopy = $age->replicate();
                        $ageCopy->entry_id = $eventEntryCopy->id;
                        $ageCopy->save();

                        foreach($age->weights as $weight)
                        {
                            $weightCopy = $weight->replicate();
                            $weightCopy->entry_id = $eventEntryCopy->id;
                            $weightCopy->entry_age_id = $ageCopy->id;
                            $weightCopy->save();
                        }
                    }

                    foreach($eventEntry->configEntriesFees as $entriesfee)
                    {
                        $entriesfeeCopy = $entriesfee->replicate();
                        $entriesfeeCopy->entry_id = $eventEntryCopy->id;
                        $entriesfeeCopy->save();
                    }
                }

                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_copy')
                );
            }
            catch(Exception $e)
            {
                $response = array(
                    'status' => 'error',
                    'msg' => trans('messages.error_copy'),
                    'errors' => $e->getMessage()
                );
            }
        }

        $data['response'] = $response;
        return view('core.alert.messages', $data);
    }

    public function includeTab()
    {
		$input = Input::all();
        $eventConfig = $this->eventConfig->find($input['event_config_id']);
        $entries = $this->eventEntries->getEntryByEventId($eventConfig->event_id);
        $configEntries = $entries->pluck('id')->toArray();

        if($input['code'] == 'general')
        {    
            $data['eventConfig'] = $eventConfig;
        }

        else if($input['code'] == 'event_entries') 
        {
           $data['entries'] = $entries;
        }

        else if($input['code'] == 'entry_config_belt') 
        {
           $configBelsts = $this->entryConfigBelt->getConfigBeltByEntryId($configEntries);
           $data['configBelsts'] = $configBelsts;
        }

        else if($input['code'] == 'entry_config_age') 
        {
            $configAges = $this->entryConfigAge->getConfigAgeByEntryId($configEntries);;
            $data['configAges'] = $configAges;
        }

        else if($input['code'] == 'entry_config_weight') 
        {
            $configAges = $this->entryConfigAge->getConfigAgeByEntryId($configEntries);
            $configAges = $configAges->pluck('id')->toArray();
            $configWeights = $this->entryConfigWeight->getEntryConfigWeightByEntryId($configEntries, $configAges);
            $data['configWeights'] = $configWeights;
        }

        else if($input['code'] == 'event_entries_fee') 
        {   
            $configEntriesFees = $this->eventEntriesFee->getEntriesFeeByEntryId($configEntries);
            $data['configEntriesFees'] = $configEntriesFees;
        }

        $data['tab_id'] = $input['tab_id'];
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.'.$input['name'], $data);
    }
}
