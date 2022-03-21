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

//Models
use member\Member as MemberModel;

use \Auth as Auth;
use Config;

use Image;

class MemberController extends Controller
{
    public $restful = true;

    public function __construct(Member $member, User $user, EventRegistration $eventRegistration)
    {
        $this->view_path = 'member';
        $this->member = $member;
        $this->user = $user;
        $this->eventRegistration = $eventRegistration;
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
        return view($this->view_path.'.add');
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

        $validator = Validator::make($input, MemberModel::rules(0));

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
                $member = $this->member->create($input);

                $profilePhoto = $input['profile_photo'];
                $idPhoto = $input['id_photo'];

                if(isset($profilePhoto) || isset($idPhoto)) 
                {
                    // $img = Image::make(file_get_contents($image))->fit($demision[0], $demision[1])->encode('data-url');
                    $img64_profilePhoto = Image::make(file_get_contents($profilePhoto))->encode('data-url');
                    $img64_idPhoto = Image::make(file_get_contents($idPhoto))->encode('data-url');

                    $member->profile_photo = $img64_profilePhoto;
                    $member->id_photo = $img64_idPhoto;

                    $member->save();
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
        $member = $this->member->find($id);
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

        $validator = Validator::make($input, MemberModel::rules($id));

        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()
            );
        } else {
			try {
                $member = $this->member->update($id, $input);

                if(!empty($input['profile_photo_remove'])) 
                {
                    if($input['profile_photo_remove'] == 'off') {
                        $member->profile_photo = null;
                        $member->save();
                    } else {
    
                        if(Input::hasfile('profile_photo')) {
        
                            $destination = $member->profile_photo;
        
                            if(File::exists($destination))
                            {
                                File::delete($destination);
                            }
        
                            $profilePhoto = Input::file('profile_photo');
        
                            $img64_profilePhoto = Image::make(file_get_contents($profilePhoto))->encode('data-url');
                            $member->profile_photo = $img64_profilePhoto;
        
                            $member->save();
                        }
                    }
                }

                if(!empty($input['id_photo_remove']))
                {
                    if($input['id_photo_remove'] == 'off') {
                        $member->id_photo = null;
                        $member->save();
                    } else {
    
                        if(Input::hasfile('id_photo')) {
    
                            $destination = $member->id_photo;
    
                            if(File::exists($destination))
                            {
                                File::delete($destination);
                            }
    
                            $idPhoto = Input::file('id_photo');
    
                            $img64_idPhoto = Image::make(file_get_contents($idPhoto))->encode('data-url');
                            $member->id_photo = $img64_idPhoto;
    
                            $member->save();
                        }
                    }
                }
            
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
                'errors' => $e
            );
        }

        $data['response'] = $response;
        return view('core.alert.messages', $data);
    }

    public function getDatatableList(Request $request)
    {
        return $this->member->getDatatableList($request);
    }


    public function showImageProfile($id)
    {
        $member = $this->member->find($id);

        if($member->profile_photo)
        {
            $data['profile_photo'] = @$member->profile_photo;
        }
        
        $returnValue['status'] = true;
        $returnValue['view'] = strval(view($this->view_path.'.show_image', $data));

        return $returnValue;
    }

    public function showImageId($id)
    {
        $member = $this->member->find($id);

        if($member->id_photo)
        {
            $data['id_photo'] = @$member->id_photo;
        }
        
        $returnValue['status'] = true;
        $returnValue['view'] = strval(view($this->view_path.'.show_image', $data));

        return $returnValue;
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
                'errors' => $e
            );
        }

        $data['response'] = $response;
        return view('core.alert.messages', $data);
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
    
    public function updateMemberStatus($memberId)
    {
        $input = Input::all();
        try {
           
            $member = $this->member->find($memberId);

            if(!empty($input['status']))
            {
                $member->status = $input['status'];
                $member->save();

                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_save')
                );
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
}
