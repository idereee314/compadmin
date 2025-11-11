<?php

namespace event;

// Repositories
use organization\OrganizationEventRepository as OrganizationEvent;
use event\EventRepository as Event;

// Models
use organization\OrganizationEvent as OrganizationEventModel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Input as Input;
use Validator;
use Config;
use Session;
use Illuminate\Http\FileHelper;

class EventOrganizerController extends Controller
{
    public function __construct(OrganizationEvent $organizationEvent, Event $event) {

        $this->view_path = "listing.event";
        $this->orgEvent = $organizationEvent;
        $this->event = $event;
    }
    
    public function index()
    {
        //
    }
    
    public function create()
    {
        $input = Input::all();
        $event = $this->event->find($input['eventId']);

        $data['event'] = $event;
        $data['roles'] = @Config::get('enums.event_organization_role');

        return View::make($this->view_path.'.event_organizer_add', $data);
    }
    
    public function store()
    {
        $input = Input::all();

        $rules = array(
            'event_id' => 'required',
            'organizations.*' => 'required',
            'role' => 'required'
        );

        $validator = Validator::make($input, $rules);

        // process the save
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
                $event = $this->event->find($input['event_id']);
                $orgArr = explode(",", $input['organizations']);

                foreach($orgArr as $org)
                {
                    $uniqOrgArr['event_id'] = $event->id;
                    $uniqOrgArr['organization_id'] = $org;
                    $uniqOrgArr['role'] = $input['role'];
                        
                    $event->organizations()->updateOrCreate($uniqOrgArr, $uniqOrgArr);
                }
                
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
        return View::make('core.alert.messages', $data);
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        $eventOrg = $this->orgEvent->find($id);
        
        $data['eventOrg'] = $eventOrg;
        $data['roles'] = @Config::get('enums.event_organization_role');
        
        return View::make($this->view_path.'.event_organizer_edit', $data);
    }
    
    public function update($id)
    {
        $input = Input::all();
        $rules = array(
            'role' => 'required'
        );
        $validator = Validator::make($input, $rules);

        // process the save
        if ($validator->fails())
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        }
        else {
            try
            {
                $even = $this->orgEvent->update($id, $input);
                
                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_save')
                );

            } 
            catch (\Illuminate\Database\QueryException $e)
            {
                $response = array(
                    'status' => 'error',
                    'msg' => trans('messages.error_save'),
                    'errors' => $e->getMessage()
                );
            }
            
        }

        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }
    
    public function destroy($id)
    {
        try {
            $this->orgEvent->delete($id);

            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_delete')
            );
        } 
        catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_delete')
            );
        }
        

        $data['response'] = $response;
        return View::make('core.alert.messages', $data);
    }
}
