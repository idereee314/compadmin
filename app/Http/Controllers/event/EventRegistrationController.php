<?php

namespace event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Input;
use Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;
use HTML;

//Repositories
use reference\EventEntriesRepository as EventEntries;
use reference\EntryConfigAgeRepository as EntryConfigAge;
use reference\EntryConfigBeltRepository as EntryConfigBelt;
use reference\EntryConfigWeightRepository as EntryConfigWeight;
use reference\ConfigMatRepository as ConfigMat;

use event\EventRegistrationRepository as EventRegistration;
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
use event\EventRefundRequestRepository as EventRefundRequest;
use event\EventTypeRepository as EventType;

//Models
use event\EventRegistration as EventRegistrationModel;
use event\EventTeamRegistration as EventTeamRegistrationModel;
use member\TeamMember as TeamMemberModel;
use member\MemberAttribute as MemberAttributeModel;

use \Auth as Auth;
use Config;
use Illuminate\Support\Str;
use \Redirect as Redirect;
use Image;
use PDF;

class EventRegistrationController extends Controller
{
    public $restful = true;

    public function __construct(Event $event, EventRegistration $eventRegistration, EventConfig $eventConfig, Academy $academy, Country $country, EventEntries $eventEntries, EntryConfigAge $configAge, EntryConfigBelt $configBelt, EntryConfigWeight $configWeight, Member $member, EventTeamRegistration $eventTeamRegistration, Team $team, TeamMember $teamMember, MemberAttribute $memberAttribute, Sport $sport, EventRefundRequest $eventRefundRequest, EventType $eventType, ConfigMat $configMat)
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
        $this->eventTeamRegistration = $eventTeamRegistration;
        $this->team = $team;
        $this->teamMember = $teamMember;
        $this->memberAttribute = $memberAttribute;
        $this->country = $country;
        $this->sport = $sport;
        $this->eventRefundRequest = $eventRefundRequest;
        $this->eventType = $eventType;
        $this->configMat = $configMat;
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
            
            
                $eventEntries = $event->entries;
                $eventRegStatusCount = $this->eventRegistration->getEventRegStatusCount($event->id)->pluck('total', 'status')->toArray();
                
                $academies = $this->academy->all();
                $countries = $this->country->all();
                $eventFees = $this->eventRegistration->getPaymentByEventId(@$input['event_id'])->groupBy('amount');
                $team_list = $this->team->all();
                $index = $this->eventType->all();
            
                $data['team_list'] = $team_list;
                $data['event'] = $event;
                $data['eventEntries'] = $event->entries;
                $data['eventRegStatusCount'] = $eventRegStatusCount;
                $data['progressPercent'] = round(@$eventRegStatusCount[@Config::get('smart.event_registration_status')['approved']] ? @$eventRegStatusCount[@Config::get('smart.event_registration_status')['approved']] / array_sum(@$eventRegStatusCount) * 100 : 0);
                $data['eventFees'] = $eventFees;
                $data['academies'] = $academies;
                $data['countries'] = $countries;
                $data['view_path'] = $this->view_path;
                if($event->config->is_team == FALSE)
                {
                    return view($this->view_path.'.index', $data);
                }
                else
                {  
                    return view($this->view_path.'.team/index_team', $data);
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

        $is_team = $this->event->find(request()->event_id)->config->is_team;
        
        $entries = $this->eventEntries->getEntryByEventId(@$input['event_id']);
        $academies = $this->academy->all();
        $team_list = $this->team->all();
        
        $data['team_list'] = $team_list;
        $data['event_id'] = @$input['event_id'];
        $data['entries'] = $entries;
        $data['academies'] = $academies;
        
        if ($is_team == false) {
            return view($this->view_path.'.add', $data);
        }
        else
        {
            return view($this->view_path.'.team/add_team', $data);
        }
        
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
        
        $is_team = $this->event->find(request()->event_id)->config->is_team;
        if ($is_team == false) {
            
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
        }
        else 
        {
            $validator = Validator::make($input, EventTeamRegistrationModel::rules(0));
            
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
                    $event = $this->eventTeamRegistration->create($input);
    
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
        
        $eventTeamRegistration = $this->eventTeamRegistration->find($id);
        $event = $eventTeamRegistration->event;
        $eventTeamAthleteRegStatusCount = $this->teamMember->getTeamRegStatusCount($eventTeamRegistration->event_id, $eventTeamRegistration->team_id)->pluck('total', 'status')->toArray();
        $athlete_list = $eventTeamRegistration->teamathlete->where('team_id', $eventTeamRegistration->team_id);
        
        $data['athlete_list'] = $athlete_list;
        $data['event_id'] = $event->id;
		$data['eventTeamRegistration'] = $eventTeamRegistration;
        $data['eventTeamAthleteRegStatusCount'] = $eventTeamAthleteRegStatusCount;
        $data['progressPercent'] = round(@$eventTeamAthleteRegStatusCount[@Config::get('smart.event_registration_status')['approved']] ? @$eventTeamAthleteRegStatusCount[@Config::get('smart.event_registration_status')['approved']] / array_sum(@$eventTeamAthleteRegStatusCount) * 100 : 0);
        $data['event'] = $event;

        return view($this->view_path.'.team/athlete_team/athlete', $data);
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
        $eventTeamRegistration = $this->eventTeamRegistration->find($id);

        if($eventTeamRegistration == null)
        {
            $academies = $this->academy->all();
            $eventEntries = $this->eventEntries->getEntryByEventId($eventRegistration->event_id);
            $configBelts = $this->configBelt->getEntryBeltByEntryId($eventRegistration->entry_id);
            $configAges = $this->configAge->getEntryAgeByEntryId($eventRegistration->entry_id);
            $configWeights = $this->configWeight->getEntryWeightByAgeId($eventRegistration->entry_age_id);
            $weight = abs($this->configWeight->find($eventRegistration->entry_weight_id)->weight);

            $data['weight'] = $weight;
            $data['eventRegistration'] = $eventRegistration;
            $data['eventEntries'] = $eventEntries;
            $data['configBelts'] = $configBelts;
            $data['configAges'] = $configAges;
            $data['configWeights'] = $configWeights;
            $data['academies'] = $academies;
    
            return view($this->view_path.'.edit', $data);
        }
        else
        {
            $is_team = $eventTeamRegistration->event->config->is_team;
            
            if ($is_team == false) {
                $academies = $this->academy->all();
                $eventEntries = $this->eventEntries->getEntryByEventId($eventRegistration->event_id);
                $configBelts = $this->configBelt->getEntryBeltByEntryId($eventRegistration->entry_id);
                $configAges = $this->configAge->getEntryAgeByEntryId($eventRegistration->entry_id);
                $configWeights = $this->configWeight->getEntryWeightByAgeId($eventRegistration->entry_age_id);   
            
                $data['eventRegistration'] = $eventRegistration;
                $data['eventEntries'] = $eventEntries;
                $data['configBelts'] = $configBelts;
                $data['configAges'] = $configAges;
                $data['configWeights'] = $configWeights;
                $data['academies'] = $academies;
            
                return view($this->view_path.'.edit', $data);
            }
            else
            {
                $eventTeamRegistration = $this->eventTeamRegistration->find($id);
                $academies = $this->academy->all();
                $eventEntries = $this->eventEntries->getEntryByEventId($eventTeamRegistration->event_id);
                $team_list = $this->team->all();

                $data['team_list'] = $team_list;
                $data['eventTeamRegistration'] = $eventTeamRegistration;
                $data['eventEntries'] = $eventEntries;
                $data['academies'] = $academies;
            
                return view($this->view_path.'.team/edit_team', $data);
            }
        }
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
    
        $eventRegistration = $this->eventRegistration->find($id);
        $eventTeamRegistration = $this->eventTeamRegistration->find($id);

        if($eventTeamRegistration == null)
        {
            $rules = [
                'entry_id' => 'required',
                'entry_age_id' => 'required',
                'entry_belt_id' => 'required',
                'entry_weight_id' => 'required',
                'academy_id' => 'required',
                //'status' => 'required'
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
        }
        else 
        {
            $is_team = $eventTeamRegistration->event->config->is_team;
    
            if ($is_team == false) {
                $rules = [
                    'entry_id' => 'required',
                    'entry_age_id' => 'required',
                    'entry_belt_id' => 'required',
                    'entry_weight_id' => 'required',
                    'academy_id' => 'required',
                    //'status' => 'required'
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
            }
            else
            {
    
                $rules = [
                    'entry_id' => 'required',
                    'academy_id' => 'required',
                    //'status' => 'required'
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
                        $event = $this->eventTeamRegistration->update($id, $input);
    
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
        $eventRegistration = $this->eventRegistration->find($id);
        $eventTeamRegistration = $this->eventTeamRegistration->find($id);
        
        if($eventTeamRegistration == null)
        {
            try {
                $eventRegistration = $this->eventRegistration->find($id);
    
                if(!empty($eventRegistration))
                {
                    if(empty($eventRegistration->status) || $eventRegistration->status == 'created')
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
                else 
                {
                    $response = array(
                        'status' => 'error',
                        'msg' => trans('messages.no_record'),
                    );
                }
              
            } catch(\Illuminate\Database\QueryException $e)
            {
                $response = array(
                    'status' => 'error',
                    'msg' => trans('messages.error_delete'),
                    'errors' => $e->getMessage()
                );
            }
        }
        else
        {
            $is_team = $eventTeamRegistration->event->config->is_team;
        
            if ($is_team == false) {
                try {
                    $eventRegistration = $this->eventRegistration->find($id);
        
                    if(!empty($eventRegistration))
                    {
                        if(empty($eventRegistration->status) || $eventRegistration->status == 'created')
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
                    else 
                    {
                        $response = array(
                            'status' => 'error',
                            'msg' => trans('messages.no_record'),
                        );
                    }
                  
                } catch(\Illuminate\Database\QueryException $e)
                {
                    $response = array(
                        'status' => 'error',
                        'msg' => trans('messages.error_delete'),
                        'errors' => $e->getMessage()
                    );
                }
            }
            else
            {
                try {
                    $eventTeamRegistration = $this->eventTeamRegistration->find($id);
        
                    if(!empty($eventTeamRegistration))
                    {
                        if(empty($eventTeamRegistration->status) || $eventTeamRegistration->status == 'created')
                        {
                            $this->eventTeamRegistration->delete($id);
        
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
                    else 
                    {
                        $response = array(
                            'status' => 'error',
                            'msg' => trans('messages.no_record'),
                        );
                    }
                  
                } catch(\Illuminate\Database\QueryException $e)
                {
                    $response = array(
                        'status' => 'error',
                        'msg' => trans('messages.error_delete'),
                        'errors' => $e->getMessage()
                    );
                }
            }
        }
        
        return $response;
    }

    public function getDatatableList(Request $request)
    {
        $is_team = @$this->event->find(@$request['event'])->config->is_team;
        if ($is_team == false) 
        {
            return $this->eventRegistration->getDatatableList($request);
        } 
        else 
        {
            return $this->eventTeamRegistration->getDatatableList($request);
        }
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
        $eventTeamRegistration = $this->eventTeamRegistration->find($input['event_reg_id']);

        $data['eventRegistration'] = $eventRegistration;
        $data['eventTeamRegistration'] = $eventTeamRegistration;

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
        $eventPage = $this->event->getEventByPage(12);
        $event = json_decode($eventPage, true);
        
        $data['view_path'] = $this->view_path;
        $data['events'] = $event['data'];
        
        $pagination = new LengthAwarePaginator($event['data'], @$event['total'], @$event['per_page'], @$event['current_page'], [
            'path'  => URL::current()
        ]);

        return view($this->view_path.'.card', $data)->with('pagination', @$pagination);
    }

    public function showPerCard($id)
    {
        $eventPage = $this->event->getEventBySportPage(12, $id);
        $event = json_decode($eventPage, true);
        
        $data['view_path'] = $this->view_path;
        $data['events'] = $event['data'];
        
        $pagination = new LengthAwarePaginator($event['data'], @$event['total'], @$event['per_page'], @$event['current_page'], [
            'path'  => URL::current()
        ]);

        return view($this->view_path.'.card', $data)->with('pagination', @$pagination);
    }

    public function showSportCard()
    {
        $sport = $this->sport->all()->toArray(); // Convert the object to an array
        $sports = $sport;
    
        $viewPath = $this->view_path;
    
        return view($this->view_path . '.sportcard', compact('viewPath', 'sports'));
    }

    public function MeduulegPrint($id)
    {        
        $eventTeamRegistration = $this->eventTeamRegistration->find($id);
        $event = $eventTeamRegistration->event;
        $eventEntries = $event->entries->find($eventTeamRegistration->entry_id);
        $athlete_list = $eventTeamRegistration->teamathlete->where('team_id', $eventTeamRegistration->team_id);
        
        $data['athlete_list'] = $athlete_list;
        $data['eventEntries'] = $eventEntries;
        $data['athlete_list'] = $athlete_list;
        $data['event_id'] = $event->id;
		$data['eventTeamRegistration'] = $eventTeamRegistration;
        $data['event'] = $event;

        return view($this->view_path.'.team/athlete_team/print', $data);    
    }

    public function generatePdf($id)
    {
        $eventTeamRegistration = $this->eventTeamRegistration->find($id);
        $event = $eventTeamRegistration->event;
        $eventEntries = $event->entries->find($eventTeamRegistration->entry_id);
        $athlete_list = $eventTeamRegistration->teamathlete->where('team_id', $eventTeamRegistration->team_id);
        
        $data['athlete_list'] = $athlete_list;
        $data['eventEntries'] = $eventEntries;
        $data['athlete_list'] = $athlete_list;
        $data['event_id'] = $event->id;
		$data['eventTeamRegistration'] = $eventTeamRegistration;
        $data['event'] = $event;

        // $pdf = PDF::loadView('pdf_meduuleg.view', $data);

        // return $pdf->download('file.pdf');

        // return PDF::loadView('pdf_meduuleg.view', $data);

        // return view($this->view_path.'.team/athlete_team/pdf_meduuleg', $data);  
        $pdf = PDF::loadView($this->view_path.'.team.athlete_team.pdf_meduuleg', $data);
        // $pdf->setPaper('a4', 'landscape');
        return $pdf->download('file.pdf');
    }

    public function printMandateByEventAndStatus()
    {
        $input = Input::all(); 
        
        $eventConfig = $this->eventConfig->findByEventId(@$input['search_event']);

        $list = $this->eventRegistration->getRegistrationByStatus(@$input['search_event'], @Config::get('smart.event_registration_status')['approved'], $input);
        
        $data['regs'] = $list->load(['academy:id,name,is_other','member:id,lastname,firstname,profile_url,birth,gender_code', 'weight:id,weight', 'entry:id,name', 'belt:id,name'])->chunk(4);
        $data['eventConfig'] = $eventConfig;
        $view = $this->view_path.'.mandat/event_credentials';
        
        if(\View::exists($view))
        {
            return view($view, $data);
        }
        else 
        {

        }
        
        /*
        $pdf = PDF::loadView($this->view_path.'.mandat_cm', $data, [], [
            'format' => 'A4-P'
        ]);

        return $pdf->stream('mandat.pdf');
        return $pdf->download('mandat.pdf');
        */
    }

    public function printCertificateByMember()
    {
        $input = Input::all();
        $sport = $this->sport;
        $eventConfig = $this->eventConfig->findByEventId(@$input['event_id']);        
        
        $registration = $this->eventRegistration->getRegistrationByMember(@$input['event_id'], @$input['member_id']);

        $view = $this->view_path.'.mandat/certificate'; 

        $data["reg"] = $registration;
        $data["eventConfig"] = $eventConfig;
        
        if(\View::exists($view))
        {
            return view($view, $data);
        }
        else 
        {

        }
        
        /*
        $pdf = PDF::loadView($this->view_path.'.mandat_cm', $data, [], [
            'format' => 'A4-P'
        ]);

        return $pdf->stream('mandat.pdf');
        return $pdf->download('mandat.pdf');
        */
    }

    public function printUrgumjlulByMember()
    {
        $input = Input::all(); 

        $eventConfig = $this->eventConfig->findByEventId(@$input['event_id']);
        
        $registration = $this->eventRegistration->getRegistrationByMember(@$input['event_id'], @$input['member_id']);
        
        $data["reg"] = $registration;
        $data["eventConfig"] = $eventConfig;
        // $view = $this->view_path.'.certificate/certificate';
        
        $pdf = PDF::loadView($this->view_path.'.certificate/certificate', $data, [], [
            'format' => 'A4-L'
        ]);

        return $pdf->stream('mandat.pdf');
        
        // if(\View::exists($view))
        // {
            
        //     return view($view, $data);
        // }
        // else 
        // {

        // }
        
        /*
        $pdf = PDF::loadView($this->view_path.'.mandat_cm', $data, [], [
            'format' => 'A4-P'
        ]);

        return $pdf->stream('mandat.pdf');
        return $pdf->download('mandat.pdf');
        */
    }

    public function treeBracket($eventId)
    {
        $event = $this->event->find($eventId);
        $eventRegistration = $this->eventRegistration->getEventRegByGroup($eventId);
        $eventRegStatusCount = $this->eventRegistration->getEventRegStatusCount($event->id)->pluck('total', 'status')->toArray();

        $data['event'] = $event;
        $data['progressPercent'] = round(@$eventRegStatusCount[@Config::get('smart.event_registration_status')['approved']] ? @$eventRegStatusCount[@Config::get('smart.event_registration_status')['approved']] / array_sum(@$eventRegStatusCount) * 100 : 0);
        $data['eventRegistration'] = $eventRegistration->groupBy(['entry.fullname', 'belt.name', 'age.name', 'weight.weight']);
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.bracket_tree', $data);
    }

    public function showBracket($eventId)
    {
        $data['view_path'] = $this->view_path;

        return view($this->view_path.'.bracket', $data);
    }
    
    public function bracketGeneration()
    {
        $input = Input::all();
        $eventId = @$input['event_id'];

        try
        {
            $entries = $this->eventRegistration->getAllEntriesFromEvent($eventId);
            //$entries = $this->eventRegistration->getAllEntriesFromEventById($eventId, 35, 42, 44, 277);

            $brackets = array();

            foreach($entries as $entry)
            {
                $members = $this->eventRegistration->getBracketMembersFromEvent($eventId, $entry->entry_id, $entry->entry_age_id, $entry->entry_belt_id, $entry->entry_weight_id, false);
                
                $participants = range(1, count($members));

                $participantsCount = count($participants);              
                $rounds = ceil(log($participantsCount)/log(2));
                $bracketSize = pow(2, $rounds);
                $requiredByes = $bracketSize - $participantsCount;

                if($participantsCount > 2)
                {
                    $firstZone = array();
                    $secondZone = array();

                    $isFirst = true;
                    $i = 0;
                    $j = 0;
                    for($k = 0; $k < $bracketSize; $k++)
                    {
                        $zoneSize =  $bracketSize / 2;
                        if($isFirst)
                        {
                            $firstZone[$i] = array_key_exists($k, $members)? $members[$k]->id: null;
                            $isFirst = false;                    

                            if($zoneSize > 2)
                            {
                                if($i + 2 == $zoneSize)
                                {
                                    $i = 1;
                                }
                                else
                                {
                                    $i = $i + 2;
                                }
                            }
                            else
                            {
                                $i++;
                            }
                        }
                        else
                        {
                            $secondZone[$j] = array_key_exists($k, $members)? $members[$k]->id: null;
                            $isFirst = true;

                            if($zoneSize > 2)
                            {
                                if($j + 2 == $zoneSize)
                                {
                                    $j = 1;
                                }
                                else
                                {
                                    $j = $j + 2;
                                }
                            }
                            else
                            {
                                $j++;
                            }
                        }
                    }
                    ksort($firstZone);
                    ksort($secondZone);
                    array_push($brackets, array('eventId' => $eventId, 'entryId' => $entry->entry_id, 
                                'ageId' => $entry->entry_age_id, 'beltId' => $entry->entry_belt_id, 
                                'weightId' => $entry->entry_weight_id, 'bracketSize' => $bracketSize,
                                'participantsCount' => $participantsCount, 'rounds' => $rounds,
                                'firstZone' => $firstZone, 'secondZone' => $secondZone));
                }
                else if($participantsCount == 2)
                {
                    $firstZone = array();

                    for($k = 0; $k < $participantsCount; $k++)
                    {
                        $firstZone[$k] = array_key_exists($k, $members)? $members[$k]->id: null;
                    }

                    array_push($brackets, array('eventId' => $eventId, 'entryId' => $entry->entry_id, 
                                'ageId' => $entry->entry_age_id, 'beltId' => $entry->entry_belt_id, 
                                'weightId' => $entry->entry_weight_id, 'bracketSize' => $bracketSize,
                                'participantsCount' => $participantsCount, 'rounds' => $rounds,
                                'firstZone' => $firstZone, 'secondZone' => null));
                }
            }
            
            foreach($brackets as $bracket)
            {
                $status = $this->eventRegistration->deleteEventBracket($bracket['eventId'], $bracket['entryId'], $bracket['ageId'], $bracket['beltId'], $bracket['weightId']);

                if($bracket['firstZone'] != null)
                {
                    for($i = 0; $i < $bracket['bracketSize'] / 2; $i = $i + 2)
                    {
                        if(array_key_exists($i, $bracket['firstZone'])) 
                        {
                            $this->eventRegistration->createEventBracket($bracket['eventId'], $bracket['entryId'], $bracket['ageId'], $bracket['beltId'], $bracket['weightId'], $bracket['firstZone'][$i], $bracket['firstZone'][$i + 1]);
                        }
                        
                    }
                }
                
                if($bracket['secondZone'] != null)
                {
                    for($i = 0; $i < $bracket['bracketSize'] / 2; $i = $i + 2)
                    {
                        if(array_key_exists($i, $bracket['secondZone'])) 
                        {
                            $this->eventRegistration->createEventBracket($bracket['eventId'], $bracket['entryId'], $bracket['ageId'], $bracket['beltId'], $bracket['weightId'], $bracket['secondZone'][$i], $bracket['secondZone'][$i + 1]);
                        }
                    }
                }
            }
            
            $response = array(
                'status' => 'success',
                'msg' => "Амжилттай оноолтыг үүсгэлээ. Оноолтын хэсгээс харна уу."
            );
        } 
        catch (\Exception $ex)
        {
            $response = array(
                'status' => 'error',
                'msg' => "Алдаа гарлаа",
                'errors' => $ex->getMessage()
            );
        }

        return $response;
    }    

    public function bracketShow($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId)
    {
        $input = Input::all();
        
        $members = $this->eventRegistration->getBracketGenerationFromEvent($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId);
        
        $total = count($members);

        $data['total'] = $total;
        $data['members'] = $members;
        $data['eventId'] = $eventId;
        $data['entryId'] = $entryId;
        $data['entryAgeId'] = $entryAgeId;
        $data['entryBeltId'] = $entryBeltId;
        $data['entryWeightId'] = $entryWeightId;
        if($total > 0)
        {
            $data['round'] = intval(log($total, 2)) + 1;
        }
        
        return view('event.bracket.generation', $data)->render();        

        //return response()->json(['html' => $html, 'eventId' => $eventId, 'entryId' => $entryId, 'entryAgeId' => $entryAgeId, 'entryBeltId' => $entryBeltId, 'entryWeightId' => $entryWeightId]); 
    }

    public function bracketPrint($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId)
    {
        $input = Input::all();
        
        $members = $this->eventRegistration->getBracketGenerationFromEvent($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId);
        
        $eventConfig =  $this->eventConfig->findByEventId($eventId);
        $entry = $this->eventEntries->find($entryId);
        $age = $this->configAge->find($entryAgeId);
        $belt = $this->configBelt->find($entryBeltId);
        $weight = $this->configWeight->find($entryWeightId);

        $total = count($members);
        
        $data['total'] = $total;
        $data['members'] = $members;
        $data['eventConfig'] = $eventConfig;
        $data['entry'] = $entry;
        $data['age'] = $age;
        $data['belt'] = $belt;
        $data['weight'] = $weight;

        if($total > 0)
        {
            $data['round'] = intval(log($total, 2)) + 1;
        }
        
        if(@$eventConfig->sport_id == 6)
        {
            return view('event.bracket.print_sambo', $data);
        }
        else
        {
            return view('event.bracket.print', $data);
        }
    }

    public function bracketEdit($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId)
    {
        $members = $this->eventRegistration->getBracketGenerationFromEvent($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId);

        $unplacedMembers = collect($members)->filter(function ($member) {
            return is_null($member->rt) || is_null($member->ro);
        });

        $mainBracketMembers = collect($members)->filter(function ($member) {
            return !is_null($member->rt) && !is_null($member->ro);
        });

        $data['eventId'] = $eventId;
        $data['entryId'] = $entryId;
        $data['entryAgeId'] = $entryAgeId;
        $data['entryBeltId'] = $entryBeltId;
        $data['entryWeightId'] = $entryWeightId;
        $data['unplacedMembers'] = $unplacedMembers;
        $data['mainBracketMembers'] = $mainBracketMembers;

        return view('event.bracket.edit', $data);
    }

    public function updateBracket(Request $request, $eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId)
    {
        $validated = $request->validate([
            'bracket_order' => 'required|json',
        ]);
    
        $bracketOrder = json_decode($validated['bracket_order'], true);
    
        foreach ($bracketOrder['unplaced'] as $order => $memberId) {
            DB::table('uq_event_brackets')
                ->where('event_id', $eventId)
                ->where('reg_one_id', $memberId)
                ->update(['entry_id' => null]);
        }
    
        foreach ($bracketOrder['mainBracket'] as $order => $memberId) {
            DB::table('uq_event_brackets')
                ->where('event_id', $eventId)
                ->where('reg_one_id', $memberId)
                ->update(['entry_id' => $order + 1]);
        }
    
        return response()->json(['success' => true]);
    }

    // jiu jitsu stats START

    public function statistics($eventId)
    {
        $event = $this->event->find($eventId);
        $eventRegistration = $this->eventRegistration->getEventRegByGroup($eventId);
        $eventRegStatusCount = $this->eventRegistration->getEventRegStatusCount($event->id)->pluck('total', 'status')->toArray();
        $eventEntries = $event->entries;
        $academies = $this->academy->all();
        $eventFees = $this->eventRegistration->getPaymentByEventId(@$eventId)->groupBy('amount');
        $eventRegistrationAcademyStats = $this->eventRegistration->getStatsAcademyFromEvent($eventId);
        $eventRegistrationAllAcademyStats = $this->eventRegistration->getStatsAcademyAllFromEvent($eventId);
        $eventRegistrationEntriesStats = $this->eventRegistration->getStatsEntriesFromEvent($eventId);
        $eventRegistrationEntriesAllStats = $this->eventRegistration->getStatsEntriesAllFromEvent($eventId);
        $eventRegistrationStatusStats = $this->eventRegistration->getStatsStatusFromEvent($eventId);
        $eventRegistrationGenderStats = $this->eventRegistration->getStatsGenderFromEvent($eventId);
        $eventRegistrationGenderAllStats = $this->eventRegistration->getStatsGenderAllFromEvent($eventId);
        $eventRegistrationOrgTypeStats = $this->eventRegistration->getStatsOrgTypeFromEvent($eventId);
        $eventRegistrationOrgTypeAllStats = $this->eventRegistration->getStatsOrgTypeAllFromEvent($eventId);
        $eventRegistrationCountryStats = $this->eventRegistration->getStatsCountryFromEvent($eventId);
        $eventRegistrationCountryAllStats = $this->eventRegistration->getStatsCountryAllFromEvent($eventId);
        $sports = $this->sport->all();
        $finance = $this->eventRegistration->getFinanceByEventId(@$eventId);
        $statsWeightForOrg = $this->eventRegistration->getStatsForOrg(@$eventId);
        $countedWeightForOrg = $this->eventRegistration->getCountedWeightForOrg(@$eventId);
        $registredWeightForOrgApproved = $this->eventRegistration->getRegistredWeightForOrgApproved(@$eventId);
        $registredWeightForOrgAll = $this->eventRegistration->getRegistredWeightForOrgAll(@$eventId);
        $registredCountedWeightForOrgApproved = $this->eventRegistration->getRegistredCountedWeightForOrgApproved(@$eventId);
        $registredCountedWeightForOrgAll = $this->eventRegistration->getRegistredCountedWeightForOrgAll(@$eventId);
        
        $data['registredCountedWeightForOrgApproved'] = $registredCountedWeightForOrgApproved;
        $data['registredCountedWeightForOrgAll'] = $registredCountedWeightForOrgAll;
        $data['registredWeightForOrgApproved'] = $registredWeightForOrgApproved;
        $data['registredWeightForOrgAll'] = $registredWeightForOrgAll;
        $data['countedWeightForOrg'] = $countedWeightForOrg;
        $data['statsWeightForOrg'] = $statsWeightForOrg;
        $data['finance'] = $finance;
        $data['event'] = $event;
        $data['progressPercent'] = round(@$eventRegStatusCount[@Config::get('smart.event_registration_status')['approved']] ? @$eventRegStatusCount[@Config::get('smart.event_registration_status')['approved']] / array_sum(@$eventRegStatusCount) * 100 : 0);
        $data['eventRegistration'] = $eventRegistration->groupBy(['entry.fullname', 'belt.name', 'age.name', 'weight.weight']);        
        $data['eventRegistrationAcademyStats'] = $eventRegistrationAcademyStats;
        $data['eventRegistrationEntriesStats'] = $eventRegistrationEntriesStats;
        $data['eventRegistrationEntriesAllStats'] = $eventRegistrationEntriesAllStats;
        $data['eventRegistrationStatusStats'] = $eventRegistrationStatusStats;
        $data['eventRegistrationGenderStats'] = $eventRegistrationGenderStats;
        $data['eventRegistrationGenderAllStats'] = $eventRegistrationGenderAllStats;
        $data['eventRegStatusCount'] = $eventRegStatusCount;
        $data['eventEntries'] = $event->entries;
        $data['academies'] = $academies;
        $data['eventFees'] = $eventFees;
        $data['events'] = $event['data'];
        $data['sports'] = $sports;
        $data['eventRegistrationOrgTypeStats'] = $eventRegistrationOrgTypeStats;
        $data['eventRegistrationOrgTypeAllStats'] = $eventRegistrationOrgTypeAllStats;
        $data['eventRegistrationCountryStats'] = $eventRegistrationCountryStats;
        $data['eventRegistrationCountryAllStats'] = $eventRegistrationCountryAllStats;
        $data['eventRegistrationAllAcademyStats'] = $eventRegistrationAllAcademyStats;
        
        $data['tabs'] = collect(Config::get("enums.event_stat_tabs"))->sortBy('order')->toArray();
        $data['tab_id'] = @$input['tab_id'] ? @$input['tab_id'] : 'tab1-1';
        $data['view_path'] = $this->view_path;

        return view('.reference/stats/stats', $data);
    }

    public function results($eventId)
    {
        $event = $this->event->find($eventId);
        $eventResult = $this->eventRegistration->getResultFromEvent($eventId);
        $getToplistByGoldMedalFromEvent = $this->eventRegistration->getToplistByGoldMedalFromEvent($eventId);
        $eventToplist = $this->eventRegistration->getToplistFromEvent($eventId);
        $eventToplistPoint = $this->eventRegistration->getToplistByPointFromEvent($eventId);
        $eventAllCategories = $this->eventRegistration->getCategoriesFromEvent($eventId);
        $statsWeightForOrg = $this->eventRegistration->getStatsForOrg(@$eventId);
        
        $resultType = null;
        $configArray = $this->eventRegistration->getEventConfig($eventId);
        if (!empty($configArray) && isset($configArray[0]->event_result_type_id)) {
            $resultType = $configArray[0]->event_result_type_id;
        }
        $genderResultFemale = $this->eventRegistration->getToplistByGoldMedalAndGenderFemaleFromEvent($eventId);
        $genderResultMale = $this->eventRegistration->getToplistByGoldMedalAndGenderMaleFromEvent($eventId);
        $genderResultPointMale = $this->eventRegistration->getToplistByPointAndGenderMaleFromEvent($eventId);
        $genderResultPointFemale = $this->eventRegistration->getToplistByPointAndGenderFemaleFromEvent($eventId);
        $eventToplistWithAthleteCount = $this->eventRegistration->getToplistWithAthleteCountFromEvent($eventId);
        
        $countedWeights = $this->eventRegistration->getCountedWeightForOrg($eventId);
        $allWeightIds = collect($countedWeights)->pluck('weight_id')->unique();
        $medaledWeightIds = collect($eventResult)->where('medal_given', true)->pluck('weight_id')->unique();
        $unawardedWeightIds = $allWeightIds->diff($medaledWeightIds);
        $medalGivenMap = $this->configWeight->getMedalGiven();
        $awardedResults = collect($eventResult)->groupBy('weight_id');
        $registeredWeights = $this->eventRegistration->getRegistredWeightForOrgApproved($eventId);

        $data['eventToplistWithAthleteCount'] = $eventToplistWithAthleteCount;
        $data['genderResultPointMale'] = $genderResultPointMale;
        $data['genderResultPointFemale'] = $genderResultPointFemale;
        $data['registeredWeights'] = $registeredWeights;
        $data['awardedResults'] = $awardedResults;
        $data['countedWeights'] = $countedWeights;
        $data['allWeightIds'] = $allWeightIds;
        $data['medaledWeightIds'] = $medaledWeightIds;
        $data['unawardedWeightIds'] = $unawardedWeightIds;
        $data['medalGivenMap'] = $medalGivenMap;
        $data['genderResultFemale'] = $genderResultFemale;
        $data['genderResultMale'] = $genderResultMale;
        $data['resultType'] = $resultType;
        $data['statsWeightForOrg'] = $statsWeightForOrg;
        $data['getToplistByGoldMedalFromEvent'] = $getToplistByGoldMedalFromEvent;
        $data['eventResult'] = $eventResult;
        $data['event'] = $event;
        $data['eventToplist'] = $eventToplist;
        $data['eventToplistPoint'] = $eventToplistPoint;
        $data['eventAllCategories'] = $eventAllCategories;
        $data['tabs'] = collect(Config::get("enums.event_result_tabs"))->sortBy('order')->toArray();
        $data['tab_id'] = @$input['tab_id'] ? @$input['tab_id'] : 'tab1-1';
        $data['view_path'] = $this->view_path;
        
        return view('.reference/result/result', $data);
    }

    //Team Member
    public function createTeamMember()
    {
        $input = Input::all();

        $academies = $this->academy->all();
        $team_list = $this->team->all();

        $data['team_list'] = $team_list;
        $data['event_id'] = @$input['event_id'];
        $data['team_id'] = @$input['team_id'];
        $data['academies'] = $academies;
        
        return view($this->view_path.'.team/athlete_team/add', $data);
    }

    public function storeTeamMember(Request $request)
    {
        $input = $request->all();
    
        $validator = Validator::make($input, TeamMemberModel::rules(0));
        if ($validator->fails()) {
            return [
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $validator->errors()->all()
            ];
        }
    
        try {
            $teamMember = $this->teamMember->create($input);
    
            $attributes = [
                [
                    'attribute_id' => 1,
                    'name' => 'athlete_height',
                ],
                [
                    'attribute_id' => 2,
                    'name' => 'athlete_weight',
                ],
                [
                    'attribute_id' => 3,
                    'name' => 'athlete_role',
                ],
                [
                    'attribute_id' => 4,
                    'name' => 'sport_title',
                ],
                [
                    'attribute_id' => 5,
                    'name' => 'jersey_number',
                ],
            ];
    
            foreach (@$attributes as $attribute) {
                @$attributeModel = new MemberAttributeModel;
                @$attributeModel->member_id = $teamMember->member_id;
                @$attributeModel->attribute_id = $attribute['attribute_id'];
                @$attributeModel->sport_id = 2;
                @$attributeModel->value = $input[$attribute['name']];
                @$attributeModel->save();
            }

            return [
                'status' => 'success',
                'msg' => trans('messages.success_save')
            ];
        } catch(\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            return [
                'status' => 'error',
                'msg' => trans('messages.error_save'),
                'errors' => $e->getMessage()
            ];
        }
    }    

    public function editTeamMember($id)
    {

        $academies = $this->academy->all();
        $teamMember = $this->teamMember->find($id);
        $team_list = $this->team->all();
        $eventEntries = $this->eventEntries->getEntryByEventId($teamMember->event_id);
        
        $data['eventEntries'] = $eventEntries;
        $data['team_id'] = $teamMember->team_id;
        $data['team_list'] = $team_list;
        $data['teamMember'] = $teamMember;
        $data['academies'] = $academies;
        $data['event_id'] = @$input['event_id'];

        return view($this->view_path.'.team/athlete_team/edit', $data);
    }

    public function updateTeamMember(Request $request, $id)
    {
        $input = $request->all();
        
        $validator = Validator::make($input, TeamMemberModel::rules($id));
    
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
                // Update the team member using the repository
                $teamMember = $this->teamMember->update($id, $input);
            
                // Update the member attribute models
                $attributes = [
                    [
                        'attribute_id' => 1,
                        'name' => 'athlete_height',
                    ],
                    [
                        'attribute_id' => 2,
                        'name' => 'athlete_weight',
                    ],
                    [
                        'attribute_id' => 3,
                        'name' => 'athlete_role',
                    ],
                    [
                        'attribute_id' => 4,
                        'name' => 'sport_title',
                    ],
                    [
                        'attribute_id' => 5,
                        'name' => 'jersey_number',
                    ],
                ];
        
                foreach ($attributes as $attribute) {
                    $attributeModel = new MemberAttributeModel;
                    $attributeModel->member_id = $teamMember->member_id;
                    $attributeModel->attribute_id = $attribute['attribute_id'];
                    $attributeModel->sport_id = 2;
                    $attributeModel->value = $input[$attribute['name']];
                    $attributeModel->save();
                }
                
                // Return a success response
                $response = array(
                    'status' => 'success',
                    'msg' => trans('messages.success_update')
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
    
        // Return the response
        return $response;
    }

    public function removeTeamMember($id)
    {
        try {
            $teamMember = TeamMember::find($id);
        
            if ($teamMember) {
                if (empty($teamMember->status) || $teamMember->status == 'created') {
                    $teamMember->delete();
                
                    $response = [
                        'status' => 'success',
                        'msg' => trans('messages.success_delete')
                    ];
                
                    return redirect()->route('event.team.member.index')->with('status', $response['msg']);
                } else {
                    $response = [
                        'status' => 'warning',
                        'msg' => 'Баталгаажуулсан хэрэглэгч устгах боломжгүй'
                    ];
                
                    return redirect()->route('event.team.member.index')->with('warning', $response['msg']);
                }
            } else {
                $response = [
                    'status' => 'error',
                    'msg' => trans('messages.no_record'),
                ];
            
                return redirect()->route('event.team.member.index')->with('error', $response['msg']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $response = [
                'status' => 'error',
                'msg' => trans('messages.error_delete'),
                'errors' => $e->getMessage()
            ];
        
            return redirect()->route('event.team.member.index')->with('error', $response['msg']);
        }
    }
    
    public function schedule($eventId)
    {
        $event = $this->event->find($eventId);
        $mat = $event->configMat;
        
        $data['mat'] = $mat;
        $data['event'] = $event;
        $data['view_path'] = $this->view_path;
        
        return view('.reference/schedule/schedule', $data);

    }

    public function weight($id)
    {
        $eventRegistration = $this->eventRegistration->find($id);

        $academies = $this->academy->all();
        $eventEntries = $this->eventEntries->getEntryByEventId($eventRegistration->event_id);
        $configBelts = $this->configBelt->getEntryBeltByEntryId($eventRegistration->entry_id);
        $configAges = $this->configAge->getEntryAgeByEntryId($eventRegistration->entry_id);
        $configWeights = $this->configWeight->getEntryWeightByAgeId($eventRegistration->entry_age_id);
        $weight = abs($this->configWeight->find($eventRegistration->entry_weight_id)->weight);
        $academyInfo = $this->academy->find($eventRegistration->academy_id);
        $checkWeight = $this->configWeight->find($eventRegistration->entry_weight_id)->weight;
        $checkAge = $this->configAge->find($eventRegistration->entry_age_id);
        $checkBelt = $this->configBelt->find($eventRegistration->entry_belt_id)->name;
        $checkEntry = $this->eventEntries->find($eventRegistration->entry_id)->name;
        $countries = $this->country->find($eventRegistration->member->country_id);
        
        $data['checkWeight'] = $checkWeight;
        $data['checkAge'] = $checkAge;
        $data['checkBelt'] = $checkBelt;
        $data['checkEntry'] = $checkEntry;
        $data['countries'] = $countries;
        $data['weight'] = $weight;
        $data['eventRegistration'] = $eventRegistration;
        $data['eventEntries'] = $eventEntries;
        $data['configBelts'] = $configBelts;
        $data['configAges'] = $configAges;
        $data['configWeights'] = $configWeights;
        $data['academies'] = $academies;
        $data['academyInfo'] = $academyInfo;

        return view($this->view_path.'.weight', $data);
    }
}
