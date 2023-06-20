<?php

namespace event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;

//Repositories
use event\EventConfigRepository as EventConfig;
use event\EventRepository as Event;
use academy\AcademyRepository as Academy;
use reference\EventEntriesRepository as EventEntries;
use reference\EntryConfigBeltRepository as EntryConfigBelt;
use reference\EntryConfigAgeRepository as EntryConfigAge;
use reference\EntryConfigWeightRepository as EntryConfigWeight;
use reference\EventEntriesFeeRepository as EventEntriesFee;
use sport\SportRepository as Sport;

//Models
use event\EventConfig as EventConfigModel;

use \Auth as Auth;
use Config;
use \HTML;
use Image;

class EventConfigController extends Controller
{
    public $restful = true;

    public function __construct(EventConfig $eventConfig, Event $event, EventEntries $eventEntries, EntryConfigBelt $entryConfigBelt, EntryConfigAge $entryConfigAge, EntryConfigWeight $entryConfigWeight, EventEntriesFee $eventEntriesFee, Sport $sport)
    {
        $this->view_path = 'event.config';
        $this->eventConfig = $eventConfig;
        $this->event = $event;
        $this->eventEntries = $eventEntries;
        $this->entryConfigBelt = $entryConfigBelt;
        $this->entryConfigAge = $entryConfigAge;
        $this->entryConfigWeight = $entryConfigWeight;
        $this->eventEntriesFee = $eventEntriesFee;
        $this->sport = $sport;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $eventConfigPage = $this->eventConfig->getEventConfigByPage(12);
        // $eventConfig = json_decode($eventConfigPage, true);

        // $data['eventConfig'] = $eventConfig['data'];
        
        $data['view_path'] = $this->view_path;
        // dd($eventConfig);
        // $pagination = new LengthAwarePaginator($eventConfig['data'], @$eventConfig['total'], @$eventConfig['per_page'], @$eventConfig['current_page'], [
        //     'path'  => URL::current()
        // ]);

        return view($this->view_path.'.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sports = $this->sport->all();
       
        $data['sports'] = $sports;
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
                'errors' => html_entity_decode(HTML::ul($validator->errors()->all()))
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
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $input = Input::all();
        $eventConfig = $this->eventConfig->find($id);
        $configBelsts = $this->entryConfigBelt->getConfigBeltByEventId($eventConfig->event_id);
        $configAges = $this->entryConfigAge->getConfigAgeByEventId($eventConfig->event_id);
        $configWeights = $this->entryConfigWeight->getConfigWeightByEventId($eventConfig->event_id);
        $configEntriesFees = $this->eventEntriesFee->getEntriesFeeByEventId($eventConfig->event_id);
        $event = $this->event->find($eventConfig->event_id);
        $sports = $this->sport->all();
       
        $data['sports'] = $sports;
        $data['eventConfig'] = $eventConfig;
        $data['entries'] = $eventConfig->event->entries;
        $data['configBelsts'] = $configBelsts->groupBy('entry_id');
        $data['configAges'] = $configAges->groupBy('entry_id');
        $data['configWeights'] = $configWeights->groupBy(['entry_id', 'entry_age_id']);
        $data['configEntriesFees'] = $configEntriesFees->groupBy('entry_id');
        $data['eventUsers'] = $event->eventUsers;

        $data['tabs'] = collect(Config::get("enums.event_config_index"))->sortBy('order')->toArray();
        $data['tab_id'] = @$input['tab_id'] ? @$input['tab_id'] : 'tab1-2';
        $data['eventConfig'] = $eventConfig;
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $eventConfig = $this->eventConfig->find($id);
        
        $data['tabs'] = collect(Config::get("enums.event_config"))->sortBy('order')->toArray();
        $data['event_config_id'] = $id;
        $data['tab_id'] = 'tab1-1';
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
                'errors' => html_entity_decode(HTML::ul($validator->errors()->all()))
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

        return $response;
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
                'errors' => $e->getMessage()
            );
        }

        return $response;
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
        $validator = Validator::make($input, EventConfigModel::rules(0));
        
        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => html_entity_decode(HTML::ul($validator->errors()->all()))
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

        return $response;
    }

    public function includeTab()
    {
		$input = Input::all();
        $eventConfig = $this->eventConfig->find($input['event_config_id']);
        $configBelsts = $this->entryConfigBelt->getConfigBeltByEventId($eventConfig->event_id);
        $configAges = $this->entryConfigAge->getConfigAgeByEventId($eventConfig->event_id);
        $configWeights = $this->entryConfigWeight->getConfigWeightByEventId($eventConfig->event_id);
        $configEntriesFees = $this->eventEntriesFee->getEntriesFeeByEventId($eventConfig->event_id);
        $event = $this->event->find($eventConfig->event_id);
        $sports = $this->sport->all();
       
        $data['sports'] = $sports;
        $data['eventConfig'] = $eventConfig;
        $data['entries'] = $eventConfig->event->entries;
        $data['configBelsts'] = $configBelsts->groupBy('entry_id');
        $data['configAges'] = $configAges->groupBy('entry_id');
        $data['configWeights'] = $configWeights->groupBy(['entry_id', 'entry_age_id']);
        $data['configEntriesFees'] = $configEntriesFees->groupBy('entry_id');
        $data['eventUsers'] = $event->eventUsers;
        
        if($input['code'] == 'entry_config_belt') 
        {
           
        }

        else if($input['code'] == 'entry_config_age') 
        {
            
            
        }

        else if($input['code'] == 'entry_config_weight') 
        {
            
            
        }

        else if($input['code'] == 'event_entries_fee') 
        {   
            
            
        }

        else if($input['code'] == 'event_event_user') 
        {   
            
            
        }

        $data['tab_id'] = $input['tab_id'];
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.'.$input['name'], $data);
    }

    // public function ranking(){
    //     $eventConfig = $this->eventConfig->find($id);
        
    //     $data['event_config_id'] = $id;

    //     $data['eventConfig'] = $eventConfig;
    //     $data['view_path'] = $this->view_path;

    //     return view($this->view_path.'.ranking', $data);
    // }

    public function ranking($sport_id){
         
        $sport = $this->sport->find($sport_id);

        $data['sport_id'] = $sport_id;
        $data['sport'] = $sport;
        $data['view_path'] = $this->view_path;

        return view('.reference/ranking/rank', $data);
    }

    public function reference($sport_id){
         
        $sport = $this->sport->find($sport_id);

        $data['sport_id'] = $sport_id;
        $data['sport'] = $sport;
        $data['tabs'] = collect(Config::get("enums.reference_tabs"))->sortBy('order')->toArray();
        $data['tab_id'] = @$input['tab_id'] ? @$input['tab_id'] : 'tab1-1';
        $data['view_path'] = $this->view_path;

        return view('.reference/reference', $data);
    }
}
