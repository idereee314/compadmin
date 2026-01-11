<?php

namespace academy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Input;
use Validator;
use \Auth as Auth;
use Config;
use Image;

//Repositories
use academy\AcademyRepository as Academy;
use organization\OrganizationRepository as Organization;
use member\MemberRepository as Member;
use sport\SportRepository as Sport;
use membership\MembershipTypeRepository as MembershipType;
use membership\MembershipAcademyRepository as MembershipAcademy;

//Models
use academy\Academy as AcademyModel;
use membership\MembershipAcademy as MembershipAcademyModel; 

class AcademyController extends Controller
{
    public $restful = true;

    public function __construct(Academy $academy, Organization $organization, Member $member, Sport $sport, MembershipType $membershipType, MembershipAcademy $membershipAcademy)
    {
        $this->view_path = 'academy';
        $this->academy = $academy;
        $this->organization = $organization;
        $this->member = $member;
        $this->sport = $sport;
        $this->membershipAcademy = $membershipAcademy;
        $this->membershipType = $membershipType;
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
        $sport = $this->sport->all();
        $data['sport'] = $sport;
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
        $sport = $this->sport->all();
        
        $data['sport'] = $sport;
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

    public function findSportByAcademy()
    {
        $input = Input::all();
        $academy = $this->academy->findSportByAcademy($input['sport_id'],$input['q']);

        return json_encode($academy);
    }

    public function academies($academyId)
    {
        $academy = $this->academy->find($academyId);
        $athlete = $this->academy->getMemberOfAcademy($academyId);
        $upcomingEventJiuJitsuData = $this->member->getUpcomingJiuJitsuEvent();
        $pastEventJiuJitsuData = $this->academy->getPastEventRegisteredAcademy($academyId);
        $eventDate = $upcomingEventJiuJitsuData[0]->event_date;
        $now = time();
        $eventTimestamp = strtotime($eventDate);
        $timeDifference = $eventTimestamp - $now;
        $daysLeft = floor($timeDifference / (60 * 60 * 24));

        $data['daysLeft'] = $daysLeft;
        $data['upcomingEventJiuJitsuData'] = $upcomingEventJiuJitsuData;
        $data['academy'] = $academy;
        $data['athlete'] = $athlete;
        $data['pastEventJiuJitsuData'] = $pastEventJiuJitsuData;
        $data['tabs'] = collect(Config::get("enums.academy_tabs"))->sortBy('order')->toArray();
        $data['tab_id'] = @$input['tab_id'] ? @$input['tab_id'] : 'tab1-1';

        return view('.reference/academyProfile/academyProfile', $data); 
    }

    public function showMergeRule()
    {
        $input = Input::all();
        
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.show_membership_list', $data);
    }

    public function indexMembershipAcademy()
    {
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.membership.index', $data);
    }

    public function createMembershipAcademyList()
    {
        $sport = $this->sport->all();
        $academy = $this->academy->academyList();
        $membershipType = $this->membershipType->all();

        $data['membershipType'] = $membershipType;
        $data['academy'] = $academy;
        $data['sport'] = $sport;
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.membership.add', $data);
    }

    public function storeMembershipAcademyList(Request $request)
    {
        $input = Input::all();

        $validator = Validator::make($input, MembershipAcademyModel::rules(0, 1));
        
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
                $academy = $this->membershipAcademy->create($input);

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

    public function editMembershipAcademyList($id)
    {
        $membership = $this->membershipAcademy->find($id);
        $academy = $this->academy->find($id);
        $sport = $this->sport->all();
        $membershipType = $this->membershipType->all();
        
        $data['membership'] = $membership;
        $data['membershipType'] = $membershipType;
        $data['sport'] = $sport;
        $data['academy'] = $academy;

        return view($this->view_path.'.membership.edit', $data);
    }

    public function updateMembershipAcademyList(Request $request, $id)
    {
        $input = Input::all();
        
        $validator = Validator::make($input, MembershipAcademyModel::rules($id));

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
                $academy = $this->membershipAcademy->update($id, $input);

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

    public function deleteMembershipAcademyList($id)
    {
        try {
            $this->membershipAcademy->delete($id);

            $response = array(
                'status' => 'success',
                'msg' => trans('messages.success_delete')
            );
            return $response;
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            $response = array(
                'status' => 'error',
                'msg' => trans('messages.error_delete'),
                'errors' => $e->getMessage()
            );
            return $response;
        }
    } 

    public function getDatatableMembershipAcademyList(Request $request)
    {
        return $this->academy->getDatatableMembershipAcademyList($request);
    }
}
