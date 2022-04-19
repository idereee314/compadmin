<?php

namespace event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Validator;

//Repositories
use event\EventUserRepository as EventUser;
use user\CompadRoleRepository as CompadRole;
use user\CompadUserRepository as CompadUser;
use event\EventRepository as Event;

//Models
use event\EventUser as EventUserModel;

use \Auth as Auth;
use Config;
use \HTML;
use Image;

class EventUserController extends Controller
{
    public $restful = true;

    public function __construct(EventUser $eventUser, CompadUser $compadUser, Event $event)
    {
        $this->view_path = 'event.user';
        $this->eventUser = $eventUser;
        $this->compadUser = $compadUser;
        $this->event = $event;
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
        $input = Input::all();

        $data['eventId'] = $input['eventId']; 
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
  
        $validator = Validator::make($input, EventUserModel::rules(0));

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
                $event = $this->event->find($input['event_id']);
                foreach($input['user_id'] as $userId)
                {
                    $unArr['event_id'] = $event->id;
                    $unArr['user_id'] = $userId;
            
                    $userArr['event_id'] = $event->id;
                    $userArr['user_id'] = $userId;

                    $event->eventUsers()->updateOrCreate($unArr, $userArr);
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
        // $eventUser = $this->eventUser->find($id);

        // $data['eventUser'] = $eventUser;
        // $data['view_path'] = $this->view_path;

        // return view($this->view_path.'.edit', $data);
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

        $validator = Validator::make($input, EventUserModel::rules($id));

        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => html_entity_decode(HTML::ul($validator->errors()->all()))
            );
        } else {
			try {

            
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
            $this->eventUser->delete($id);

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
        return $this->eventConfig->getDatatableList($request);
    }

}
