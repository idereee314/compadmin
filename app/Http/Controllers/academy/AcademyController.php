<?php

namespace academy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Input;
use Validator;

//Repositories
use academy\AcademyRepository as Academy;
use organization\OrganizationRepository as Organization;
use member\MemberRepository as Member;

//Models
use academy\Academy as AcademyModel;

use \Auth as Auth;
use Config;

use Image;

class AcademyController extends Controller
{
    public $restful = true;

    public function __construct(Academy $academy, Organization $organization, Member $member)
    {
        $this->view_path = 'academy';
        $this->academy = $academy;
        $this->organization = $organization;
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

        $validator = Validator::make($input, AcademyModel::rules(0));

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
                $academy = $this->academy->create($input);

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
        // $data['response'] = $response;
        // return view('core.alert.messages', $data);
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
        $academy = $this->academy->find($id);
        $data['academy'] = $academy;

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

        $validator = Validator::make($input, AcademyModel::rules($id));

        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        } else {
			try {
                $academy = $this->academy->update($id, $input);

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

        // $data['response'] = $response;
        // return view('core.alert.messages', $data);
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
        
            $this->academy->delete($id);

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

        // $data['response'] = $response;
        // return view('core.alert.messages', $data);
        return $response;
    }

    public function getDatatableList(Request $request)
    {
        return $this->academy->getDatatableList($request);
    }
    
    public function getIsOther()
    {
        $input = Input::all();

        $academy = $this->academy->find($input['academy_id']);
        return json_encode($academy->is_other);
    }

    public function findOrganizationByName()
    {
        $input = Input::all();
        $organization = $this->organization->findOrganizationByName($input['q']);

        return json_encode($organization);
    }

    public function academies($academyId)
    {
        $academy = $this->academy->find($academyId);
        $athlete = $this->academy->getMemberOfAcademy($academyId);
        $upcomingEventJiuJitsuData = $this->member->getUpcomingJiuJitsuEvent();
        $pastEventJiuJitsuData = $this->academy->getPastEventRegisteredAcademy($academyId);
        // dd($pastEventJiuJitsuData);
        //days left start
        // Check if there are upcoming events before proceeding
        $eventDate = $upcomingEventJiuJitsuData[0]->event_date;
        $now = time();
        $eventTimestamp = strtotime($eventDate);
        $timeDifference = $eventTimestamp - $now;
        // Convert the time difference to days
        $daysLeft = floor($timeDifference / (60 * 60 * 24));
        //days left end

        $data['daysLeft'] = $daysLeft;
        $data['upcomingEventJiuJitsuData'] = $upcomingEventJiuJitsuData;
        $data['academy'] = $academy;
        $data['athlete'] = $athlete;
        $data['pastEventJiuJitsuData'] = $pastEventJiuJitsuData;
        $data['tabs'] = collect(Config::get("enums.academy_tabs"))->sortBy('order')->toArray();
        $data['tab_id'] = @$input['tab_id'] ? @$input['tab_id'] : 'tab1-1';

        return view('.reference/academyProfile/academyProfile', $data); 
    }

}
