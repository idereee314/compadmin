<?php

namespace event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;

//Repositories
use reference\EventEntriesRepository as EventEntries;
use reference\EntryConfigAgeRepository as EntryConfigAge;
use reference\EntryConfigBeltRepository as EntryConfigBelt;
use reference\EntryConfigWeightRepository as EntryConfigWeight;

use event\EventRegistrationRepository as EventRegistration;
use event\EventConfigRepository as EventConfig;
use event\EventRepository as Event;
use academy\AcademyRepository as Academy;
use member\MemberRepository as Member;

//Models
use event\EventRegistration as EventRegistrationModel;

use \Auth as Auth;
use Config;
use Illuminate\Support\Str;
use \Redirect as Redirect;
use Image;

class EventRegistrationController extends Controller
{
    public $restful = true;

    public function __construct(Event $event, EventRegistration $eventRegistration, EventConfig $eventConfig, Academy $academy, EventEntries $eventEntries, EntryConfigAge $configAge, EntryConfigBelt $configBelt, EntryConfigWeight $configWeight, Member $member)
    {
        $this->view_path = 'event.registration';
        $this->event = $event;
        $this->eventRegistration = $eventRegistration;
        $this->eventConfig = $eventConfig;
        $this->academy = $academy;
        $this->eventEntries = $eventEntries;
        $this->configAge = $configAge;
        $this->configBelt = $configBelt;
        $this->configWeight = $configWeight;
        $this->member = $member;
        $this->view_path = "event.registration";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $input = Input::all();

        if(@$input['event_id'])
        {
            $event = $this->event->find(@$input['event_id']);

            $permission = $event->users->contains(Auth::user()->id);
            if($permission || Auth::user()->roles->first()->code == 'admin')
            {
                $eventEntries = $event->entries;
                $eventRegStatusCount = $this->eventRegistration->getEventRegStatusCount($event->id)->pluck('total', 'status')->toArray();
                $academies = $this->academy->all();
        
                $data['event'] = $event;
                $data['eventEntries'] = $event->entries;
                $data['eventRegStatusCount'] = $eventRegStatusCount;
                $data['academies'] = $academies;
                $data['view_path'] = $this->view_path;
        
                return view($this->view_path.'.index', $data);
            }
            else 
            {
                return Redirect::route('event.competition.card');
            }
        
        }
        else 
        {
            return Redirect::route('event.competition.card');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $input = Input::all();
        $entries = $this->eventEntries->getEntryByEventId(@$input['event_id']);
        $academy = $this->academy->all();

        $data['event_id'] = @$input['event_id'];
        $data['entries'] = $entries;
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
                'errors' => html_entity_decode(HTML::ul($validator->errors()->all()))
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
        $eventRegistration = $this->eventRegistration->find($id);
        $competitions = $this->eventConfig->getRegistringComp(@$now);
        $academy = $this->academy->all();
        $eventEntries = $this->eventEntries->getEntryByEventId($eventRegistration->event_id);
        $configBelts = $this->configBelt->getEntryBeltByEntryId($eventRegistration->entry_id);
        $configAges = $this->configAge->getEntryAgeByEntryId($eventRegistration->entry_id);
        $configWeights = $this->configWeight->getEntryWeightByAgeId($eventRegistration->entry_age_id);   

        $data['eventRegistration'] = $eventRegistration;
        $data['competitions'] = $competitions;
        $data['eventEntries'] = $eventEntries;
        $data['configBelts'] = $configBelts;
        $data['configAges'] = $configAges;
        $data['configWeights'] = $configWeights;
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
                'errors' => html_entity_decode(HTML::ul($validator->errors()->all()))
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
                        'msg' => 'Баталгаажуулсан хэрэглэгч устгах боломжгүй'                    
                    );
                }
            }
          
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
        return $this->eventRegistration->getDatatableList($request);
    }

    public function getConfigByEntryId()
    {
        $input = Input::all();
        $config = array();
		if(@$input['entry_id'])
		{
			$belts = $this->configBelt->getEntryBeltByEntryId($input['entry_id']);
            $ages = $this->configAge->getEntryAgeByEntryId($input['entry_id']);

            $config['belt'] = $belts;
            $config['age'] = $ages;
		}
        return json_encode(@$config);
    }

    public function createPlace()
    {
        $input = Input::all();
        $eventRegistration = $this->eventRegistration->find($input['event_reg_id']);

        $data['eventRegistration'] = $eventRegistration;

        return view($this->view_path.'.award', $data);
    }

    public function takePlace(Request $request)
    {
        $input = Input::all();
        $eventRegistration = $this->eventRegistration->find($input['event_registration_id']);
        
        try
        {
            $uniqArr['event_registration_id'] = $eventRegistration->id;
            $uniqArr['member_id'] = $eventRegistration->member_id;

            $inputArr['event_registration_id'] = $eventRegistration->id;
            $inputArr['member_id'] = $eventRegistration->member_id;
            $inputArr['place_number'] = $input['place_number'];

            $eventRegistration->award()->updateOrCreate($uniqArr, $inputArr); 
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

        return $response;
    }

    public function showCard()
    {
        $eventPage = $this->event->getEventByPage(6);
        $event = json_decode($eventPage, true);
        
        $data['view_path'] = $this->view_path;
        $data['events'] = $event['data'];
        //dd($event);
        $pagination = new LengthAwarePaginator($event['data'], @$event['total'], @$event['per_page'], @$event['current_page'], [
            'path'  => URL::current(),
            'query' => @$searchData,
        ]);

        return view($this->view_path.'.card', $data)->with('pagination', @$pagination);
    }
}
