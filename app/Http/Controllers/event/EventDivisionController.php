<?php

namespace event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Input;
use Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;

//Repositories
use event\EventDivisionRepository as EventDivision;
use reference\EventEntriesRepository as EventEntries;
use reference\EntryConfigBeltRepository as EntryConfigBelt;
use reference\EntryConfigAgeRepository as EntryConfigAge;
use reference\EntryConfigWeightRepository as EntryConfigWeight;
use event\EventRegistrationRepository as EventRegistration;
use event\EventRepository as Event;


//Models
use event\EventDivision as EventDivisionModel;

use \Auth as Auth;
use Config;
use \HTML;
use Image;
use Log;
use Carbon;

class EventDivisionController extends Controller
{
    public $restful = true;

    public function __construct(EventDivision $eventDivision, Event $event, EventEntries $eventEntries, EntryConfigBelt $entryConfigBelt, EntryConfigAge $entryConfigAge, EntryConfigWeight $entryConfigWeight, EventRegistration $eventRegistration)
    {
        $this->view_path = 'event.award';
        $this->eventDivision = $eventDivision;
        $this->eventEntries = $eventEntries;
        $this->entryConfigBelt = $entryConfigBelt;
        $this->entryConfigAge = $entryConfigAge;
        $this->entryConfigWeight = $entryConfigWeight;
        $this->eventRegistration = $eventRegistration;
        $this->event = $event;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($eventId)
    {
        $event = $this->event->find($eventId);
        
        $data['event'] = $event;
        $data['eventId'] = $eventId;
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
        $validator = Validator::make($input, EventDivisionModel::rules(0));
        
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
                $event = $this->eventDivision->create($input);
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
        
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($eventId, $id)
    {
        $input = Input::all();
        
        $division = $this->eventDivision->find($id);
        
        $data['eventId'] = $eventId;
        $data['division'] = $division;
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
    public function update(Request $request, $eventId, $id)
    {
        try {
            $input = Input::all();
            
            $this->eventDivision->update($id, $input);

            return response()->json([
                'status' => 'success',
                'msg'    => trans('messages.success_update'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg'    => trans('messages.error_save'),
                'errors' => $e->getMessage(),
            ], 500);
        }
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
        
            $this->eventDivision->delete($id);

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

    public function getDatatableList(Request $request, $eventId)
    {
        return $this->eventDivision->getDatatableList($request, $eventId);
    }
}
