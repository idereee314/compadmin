<?php

namespace event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;
use HTML;

//Repositories
use reference\EventEntriesRepository as EventEntries;
use reference\EntryConfigAgeRepository as EntryConfigAge;
use reference\EntryConfigBeltRepository as EntryConfigBelt;
use reference\EntryConfigWeightRepository as EntryConfigWeight;

use event\EventRefundRequestRepository as EventRefundRequest;
use event\EventTeamRegistrationRepository as EventTeamRegistration;
use event\EventConfigRepository as EventConfig;
use event\EventRepository as Event;
use academy\AcademyRepository as Academy;
use member\MemberRepository as Member;
use team\TeamRepository as Team;
use member\TeamMemberRepository as TeamMember;
use member\TeamMemberAttributeRepository as MemberAttribute;
use country\CountryRepository as Country;
use sport\SportRepository as Sport;

//Models
use event\EventRefundRequest as EventRefundRequestModel;

use \Auth as Auth;
use Config;
use Illuminate\Support\Str;
use \Redirect as Redirect;
use Image;
use PDF;

class EventRefundRequestController extends Controller
{
    public $restful = true;

    public function __construct(Event $event, EventRefundRequest $eventRefundRequest, EventConfig $eventConfig, Academy $academy, Country $country,Member $member, Sport $sport)
    {
        $this->view_path = 'event.registration';
        $this->event = $event;
        $this->eventRefundRequest = $eventRefundRequest;
        $this->eventConfig = $eventConfig;
        $this->academy = $academy;
        $this->member = $member;
        $this->country = $country;
        $this->sport = $sport;
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
            
            $eventRegStatusCount = $this->eventRefundRequest->getEventRegStatusCount($event->id)->pluck('total', 'status')->toArray();
            $academies = $this->academy->all();
            $countries = $this->country->all();
            $eventFees = $this->eventRefundRequest->getPaymentByEventId(@$input['event_id'])->groupBy('amount');
            
            $data['event'] = $event;
            $data['eventRegStatusCount'] = $eventRegStatusCount;
            $data['progressPercent'] = round(@$eventRegStatusCount[@Config::get('smart.event_refund_request_status')['approved']] ? @$eventRegStatusCount[@Config::get('smart.event_registration_status')['approved']] / array_sum(@$eventRegStatusCount) * 100 : 0);
            $data['eventFees'] = $eventFees;
            $data['academies'] = $academies;
            $data['countries'] = $countries;
            $data['eventRefundRequest'] = $event->eventRefundRequest; 
            $data['view_path'] = $this->view_path;
            
            return view($this->view_path.'.refundRequest', $data);

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
        
        $event = $this->event->find(@$input['eventId']);
        $countries = $this->country->all();
        $academies = $this->academy->all();

        $data['event'] = @$event;
        $data['event_id'] = @$event->id;
        $data['academies'] = $academies;
        $data['countries'] = $countries;
        $data['eventRefundRequest'] = $event->eventRefundRequest;
        
        return view($this->view_path.'.refundRequest/add', $data);
        
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
        
        $validator = Validator::make($input, EventRefundRequestModel::rules(0));

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
                $event = $this->eventRefundRequest->create($input);

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

        $eventRefundRequest = $this->eventRefundRequest->find($id);        
        $event = $this->event->find(@$input['eventId']);
        $countries = $this->country->all();
        $academies = $this->academy->all();
        
        $data['event_id'] = @$input['event_id'];
        $data['academies'] = $academies;
        $data['countries'] = $countries;
        $data['eventRefundRequest'] = $eventRefundRequest;

        return view($this->view_path.'.refundRequest/edit', $data);
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

        $validator = Validator::make($input, EventRefundRequestModel::rules($id));

        if ($validator->fails())
		{
        	$response = array(
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => html_entity_decode(HTML::ul($validator->errors()->all()))
            );
        } else {
			try {
                $event = $this->eventRefundRequest->update($id, $input);
            
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
            $this->eventRefundRequest->delete($id);

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
        return $this->eventRefundRequest->getDatatableList($request);
    }

}
