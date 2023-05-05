<?php

namespace member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Validator;

//Repositories
use member\MemberRepository as Member;
use user\UserRepository as User;
use event\EventRegistrationRepository as EventRegistration;
use country\CountryRepository as Country;

//Models
use member\Member as MemberModel;
use country\Country as CountryModel;

use \Auth as Auth;
use Config;
use \HTML;
use Image;
use Str;

class MemberController extends Controller
{
    public $restful = true;

    public function __construct(Member $member, User $user, EventRegistration $eventRegistration, Country $country)
    {
        $this->view_path = 'member';
        $this->member = $member;
        $this->user = $user;
        $this->eventRegistration = $eventRegistration;
        $this->country = $country;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $memberGenderCount = $this->member->getGenderCode()->pluck('total', 'gender_code')->toArray();

        $data['memberGenderCount'] = $memberGenderCount;
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
        $countries = $this->country->all();
        
        $data['countries'] = $countries;
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
        // dd($input);
        // $input['register_number'] = Str::upper($input['register_number']);

        $validator = Validator::make($input, MemberModel::rules(0));
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
                $member = $this->member->create($input);
                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_save')
                );

                $profileImage = Input::file('profile_photo');
                $idImage = Input::file('id_photo');
                
                if(@$profileImage)
                {
                    $imageName = 'memberpr_' . date('YmdHis') . '_' . uniqid() . '.jpg';
                    $profileImagePathS3 = "/member/".$member->id."/".$imageName;

                    $image = Image::make($profileImage);
                    $imageData = $image->encode('jpg');
                    
                    try 
                    {
                        \Storage::disk('s3')->put($profileImagePathS3, (string)$imageData, 'public');
                        $member->profile_url = $profileImagePathS3;
                    }
                    catch(\Exception $e)
                    {
                        $validator->errors()->add('', $e->getMessage());
                    }
                }
                
                if(@$idImage)
                {
                    $imageName = 'memberid_' . date('YmdHis') . '_' . uniqid() . '.jpg';
                    $idImagePathS3 = "/member/".$member->id."/".$imageName;

                    $image = Image::make($idImage);
                    $imageData = $image->encode('jpg');

                    try 
                    {
                        \Storage::disk('s3')->put($idImagePathS3, $imageData, 'public');
                        $member->id_url = $idImagePathS3;
                    }
                    catch(\Exception $e)
                    {
                        $validator->errors()->add('', $e->getMessage());
                    }
                }

                if(count($validator->errors()) == 0) 
                {
                    $member->save();
                }

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
        return @$response;
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
        $member = $this->member->find($id);
        $countries = $this->country->all();
        
        $data['countries'] = $countries;
        $data['member'] = $member;

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
        $profileImage = Input::file('profile_photo');
        $idImage = Input::file('id_photo');
        $member = $this->member->find($id);

        $validator = Validator::make($input, MemberModel::rules($id));

        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => html_entity_decode(HTML::ul($validator->errors()->all()))
            );
        } else {
			try {
                if(@$profileImage)
                {
                    if (\Storage::disk('s3')->exists(@$member->profile_url)) {                
                        \Storage::disk('s3')->delete($member->profile_url);
                    }

                    $imageName = 'memberpr_' . date('YmdHis') . '_' . uniqid() . '.jpg';
                    $profileImagePathS3 = "/member/".$member->id."/".$imageName;

                    $image = Image::make($profileImage);
                    $imageData = $image->encode('jpg');
                    
                    try 
                    {
                        \Storage::disk('s3')->put($profileImagePathS3, (string)$imageData, 'public');
                        $input['profile_url'] = $profileImagePathS3;
                    }
                    catch(\Exception $e)
                    {
                        $validator->errors()->add('', $e->getMessage());
                    }
                }

                if(@$idImage)
                {
                    if (\Storage::disk('s3')->exists(@$member->id_url)) {                
                        \Storage::disk('s3')->delete(@$member->id_url);
                    }

                    $imageName = 'memberid_' . date('YmdHis') . '_' . uniqid() . '.jpg';
                    $idImagePathS3 = "/member/".$member->id."/".$imageName;

                    $image = Image::make($idImage);
                    $imageData = $image->encode('jpg');

                    try 
                    {
                        \Storage::disk('s3')->put($idImagePathS3, $imageData, 'public');
                        $input['id_url'] = $idImagePathS3;
                    }
                    catch(\Exception $e)
                    {
                        $validator->errors()->add('', $e->getMessage());
                    }
                }
                $this->member->update($id, $input);
            
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
        
            $this->member->delete($id);

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
        return $this->member->getDatatableList($request);
    }


    public function showImage($type, $id)
    {
        $member = $this->member->find($id);
        if($type == 'profile')
        {
            $data['imageUrl'] = $member->profile_url;
        }
        else if($type == 'id')
        {
            $data['imageUrl'] = $member->id_url;
        }

        return view($this->view_path.'.show_image', $data);
    }

    public function createConnectUser($id)
    {
        $member = $this->member->find($id);

        $data['id'] = $id;

        if(!empty($member->user_id))
        {
            @$data['firstname'] = $this->user->find($member->user_id)->firstname;
        }

        $returnValue['status'] = true;
        $returnValue['view'] = strval(view($this->view_path.'.connect_user', $data));

        return $returnValue;
    }

    public function updateConnectUser($memberId)
    {
        $input = Input::all();
        try {
           
            $member = $this->member->find($memberId);

            if(!empty($member->user_id))
            {
                $member->user_id = null;
                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_disconnect')
                );
            }
            else
            {
                $member->user_id = $input['user_id'];
                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_connect')
                );
            }
            
            $member->save();
    
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

    public function searchMember()
    {
        $input = Input::all();

        $members = $this->member->searchMember(@$input['q']);
        return json_encode($members);
    }

    public function searchUser()
    {
        $input = Input::all();
        $users = $this->user->searchUser(@$input['q']);
        return json_encode($users);
    }

    public function createMemberStatus($id)
    {
        $member = $this->member->find($id);
        $data['member'] = $member;
        
        $returnValue['status'] = true;
        $returnValue['view'] = strval(view($this->view_path.'.member_status', $data));

        return $returnValue;
    }

    public function memberListByEvent($memberId)
    {   
        try
        {
            $members = $this->member->memberListByEvent($memberId);

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

    //Profile
    public function profile($memberId)
    {
        $member = $this->member->find($memberId);
        $memberApprovedData = $this->member->getMemberToProfileApprovedData($memberId);
        $memberAllData = $this->member->getMemberToProfileAllData($memberId);
        $upcomingEventJiuJitsuData = $this->member->getUpcomingJiuJitsuEvent();
        $pastEventJiuJitsuData = $this->member->getPastJiuJitsuEvent();
        $athleteAcademyInfo = $this->member->getAthleteAcademyInfo($memberId);
        $athleteSchoolInfo = $this->member->getAthleteSchoolInfo($memberId);
        $athleteUniversityInfo = $this->member->getAthleteUniversityInfo($memberId);
        $countries = $this->country->find($member->country_id);
        
        $data['countries'] = $countries;
        $data['pastEventJiuJitsuData'] = $pastEventJiuJitsuData;
        $data['member'] = $member;
        $data['memberApprovedData'] = $memberApprovedData;
        $data['memberAllData'] = $memberAllData;
        $data['upcomingEventJiuJitsuData'] = $upcomingEventJiuJitsuData;
        $data['athleteAcademyInfo'] = $athleteAcademyInfo;
        $data['athleteSchoolInfo'] = $athleteSchoolInfo;
        $data['athleteUniversityInfo'] = $athleteUniversityInfo;

        return view('.reference/profile/profile', $data); 
    }

    public function profileResult($memberId)
    {
        $member = $this->member->find($memberId);
        $memberApprovedData = $this->member->getMemberToProfileApprovedData($memberId);
        $memberAllData = $this->member->getMemberToProfileAllData($memberId);
        // dd($memberData);

        $data['member'] = $member;
        $data['memberApprovedData'] = $memberApprovedData;
        $data['memberAllData'] = $memberAllData;

        return view('.reference/profile/profileResults', $data); 
    }

    public function profileEvent($memberId)
    {
        $member = $this->member->find($memberId);
        $memberApprovedData = $this->member->getMemberToProfileApprovedData($memberId);
        $memberAllData = $this->member->getMemberToProfileAllData($memberId);
        
        $data['member'] = $member;
        $data['memberApprovedData'] = $memberApprovedData;
        $data['memberAllData'] = $memberAllData;

        return view('.reference/profile/profileEvents', $data); 
    }

    public function profileUpcoming()
    {
        $upcomingEventJiuJitsuData = $this->member->getUpcomingJiuJitsuEvent();

        $data['upcomingEventJiuJitsuData'] = $upcomingEventJiuJitsuData;

        return view('.reference/profile/upcomingEvent', $data); 
    }

    public function profilePastEvent()
    {
        $pastEventJiuJitsuData = $this->member->getPastJiuJitsuEvent();

        $data['pastEventJiuJitsuData'] = $pastEventJiuJitsuData;

        return view('.reference/profile/pastEvent', $data); 
    }    
}
