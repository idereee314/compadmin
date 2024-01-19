<?php

namespace reference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Input;
use Validator;

//Repositories
use reference\EntryConfigBeltRepository as EventEntryBelt;
use reference\EventEntriesRepository as EventEntry;
use reference\BeltGroupRepository as BeltGroup;

//Models
use reference\EntryConfigBelt as EventEntryBeltModel;

use \Auth as Auth;
use Config;

use Image;

class EventEntryBeltController extends Controller
{
    public $restful = true;

    public function __construct(EventEntryBelt $eventEntryBelt, EventEntry $eventEntry,BeltGroup $beltGroup)
    {
        $this->view_path = 'event.entry.belt';
        $this->eventEntryBelt = $eventEntryBelt;
        $this->eventEntry = $eventEntry;
        $this->beltGroup = $beltGroup;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $beltGroup = $this->beltGroup->all();
        
        $data['beltGroup'] = $beltGroup;
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
        $input = Input::all();
        $beltGroup = $this->beltGroup->all();
        
        $data['beltGroup'] = $beltGroup;
        $data['eventEntries'] = $this->eventEntry->getEntryByEventId($input['eventId']);
        $data['eventId'] = $input['eventId'];

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
        $validator = Validator::make($input, EventEntryBeltModel::$rules);

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
                $event = $this->eventEntryBelt->create($input);
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
        $input = Input::all();
        $eventEntryBelt = $this->eventEntryBelt->find($id);
        $eventEntries = $this->eventEntry->getEntryByEventId(@$input['eventId']);
        $beltGroup = $this->beltGroup->all();

        $data['beltGroup'] = $beltGroup;
        $data['eventEntries'] = $eventEntries;
        $data['eventEntryBelt'] = $eventEntryBelt;

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

        $validator = Validator::make($input, EventEntryBeltModel::$rules);

        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => html_entity_decode(HTML::ul($validator->errors()->all()))
            );
        } else {
			try {
                $event = $this->eventEntryBelt->update($id, $input);
            
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
        
            $this->eventEntryBelt->delete($id);

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

        return $response;
    }

    public function getDatatableList(Request $request)
    {
        return $this->eventEntryBelt->getDatatableList($request);
    }

    public function getEntryBeltByEntryId()
    {
        $input = Input::all();
        $belts = $this->eventEntryBelt->getEntryBeltByEntryId(@$input['entry_id']);    

        return json_encode($belts);
    }

}
