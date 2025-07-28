<?php

namespace event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Input;
use Validator;

//Repositories
use event\EventUserRepository as EventUser;
use user\CompadRoleRepository as CompadRole;
use user\CompadUserRepository as CompadUser;
use event\EventRepository as Event;
use sport\SportRepository as Sport;
use location\unit\AimagCityRepository as AimagCity;
use reference\PictureTypeRepository as PictureType;

//Models
use event\Event as EventModel;

use \Auth as Auth;
use Config;
use \HTML;
use Image;

class EventController extends Controller
{
    public $restful = true;

    public function __construct(Event $event, CompadUser $compadUser, Sport $sport, AimagCity $aimagCity, PictureType $pictureType)
    {
        $this->view_path = 'event.listing';
        $this->compadUser = $compadUser;
        $this->event = $event;
        $this->sport = $sport;
        $this->aimagCity = $aimagCity;
        $this->pictureType = $pictureType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $event = $this->event->all();

        $data['events'] = $event;
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
        $sports = $this->sport->all();
        $status = @Config::get('smart.event_status');
        $pictureType = $this->pictureType->all()->where('object_type', Config::get('smart.object_types')[1])->first();
        $eventOrganizationRoles = @Config::get('enums.event_organization_role');
        $aimagCity = $this->aimagCity->all();

        $data['statuses'] = $status;
        $data['pictureType'] = $pictureType;
        $data['roles'] = $eventOrganizationRoles;
        $data['aimagCity'] = $aimagCity;
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
        $input = Input::all();
        $event = $this->event->find($id);
        
        $data['event'] = $event;

        return view($this->view_path.'.description', $data);
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
        return $this->event->getDatatableList($request);
    }

}
