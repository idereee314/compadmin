<?php

namespace event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Validator;

//Repositories
use event\EventRegistrationRepository as EventRegistration;
use event\EventConfigRepository as EventConfig;
use academy\AcademyRepository as Academy;
use member\MemberRepository as Member;

//Models
use event\EventRegistration as EventRegistrationModel;

use \Auth as Auth;
use Config;
use Illuminate\Support\Str;

use Image;

class EventRegistrationController extends Controller
{
    public $restful = true;

    public function __construct(EventRegistration $eventRegistration, EventConfig $eventConfig, Academy $academy, Member $member)
    {
        $this->view_path = 'event.registration';
        $this->eventRegistration = $eventRegistration;
        $this->eventConfig = $eventConfig;
        $this->academy = $academy;
        $this->member = $member;
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
        //$now = Carbon\Carbon::now()->toDateTimeString();
        $competitions = $this->eventConfig->getRegistringComp(@$now);
        $academy = $this->academy->all();

        $data['competitions'] = $competitions;
        $data['academies'] = $academy;

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
        $validator = Validator::make($input, EventRegistrationModel::rules(0));

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
  
                $event = $this->eventRegistration->create($input);

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
        $eventRegistration = $this->eventRegistration->find($id);
        $data['eventRegistration'] = $eventRegistration;
        $competitions = $this->eventConfig->getRegistringComp(@$now);
        $academy = $this->academy->all();

        $member = $this->member->find($eventRegistration->member_id);        

        $data['competitions'] = $competitions;
        $data['firstname'] = $member->firstname;
        $data['academies'] = $academy;

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

        $rules = [
            'entry_id' => 'required',
            'entry_age_id' => 'required',
            'entry_belt_id' => 'required',
            'entry_weight_id' => 'required',
            'academy_id' => 'required',
            'status' => 'required'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        } else {
			try {
                $event = $this->eventRegistration->update($id, $input);

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
            $eventRegistration = $this->eventRegistration->find($id);
            if(!empty($eventRegistration))
            {
                if($eventRegistration->status == 'created')
                {
                    $this->eventRegistration->delete($id);

                    $response = array(
                        'status' => 'success',
                        'msg' => trans('messages.success_delete')
                    );
                }
                else
                {
                    $response = array(
                        'status' => 'warning',
                        'msg' => 'Баталгаажуулсан хэрэглэгч устгах боломжгүй'                    );
                }
            }
          
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
        return $this->eventRegistration->getDatatableList($request);
    }

}
