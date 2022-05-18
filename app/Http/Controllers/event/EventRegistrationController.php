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
use PDF;

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
            $eventFees = $this->eventRegistration->getPaymentByEventId(@$input['event_id'])->groupBy('amount');

            $data['event'] = $event;
            $data['eventEntries'] = $event->entries;
            $data['eventRegStatusCount'] = $eventRegStatusCount;
            $data['progressPercent'] = round(@$eventRegStatusCount[@Config::get('smart.event_registration_status')['approved']] ? @$eventRegStatusCount[@Config::get('smart.event_registration_status')['approved']] / array_sum(@$eventRegStatusCount) * 100 : 0);
            $data['eventFees'] = $eventFees;
            $data['academies'] = $academies;
            $data['view_path'] = $this->view_path;
    
            return view($this->view_path.'.index', $data);
        
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
        $academy = $this->academy->all();
        $eventEntries = $this->eventEntries->getEntryByEventId($eventRegistration->event_id);
        $configBelts = $this->configBelt->getEntryBeltByEntryId($eventRegistration->entry_id);
        $configAges = $this->configAge->getEntryAgeByEntryId($eventRegistration->entry_id);
        $configWeights = $this->configWeight->getEntryWeightByAgeId($eventRegistration->entry_age_id);   

        $data['eventRegistration'] = $eventRegistration;
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
        $eventPage = $this->event->getEventByPage(12);
        $event = json_decode($eventPage, true);
        
        $data['view_path'] = $this->view_path;
        $data['events'] = $event['data'];
        
        //dd($event);
        $pagination = new LengthAwarePaginator($event['data'], @$event['total'], @$event['per_page'], @$event['current_page'], [
            'path'  => URL::current()
        ]);

        return view($this->view_path.'.card', $data)->with('pagination', @$pagination);
    }

    public function printMandateByEventAndStatus()
    {
        $input = Input::all();
        $list = $this->eventRegistration->getRegistrationByStatus(@$input['search_event'], @Config::get('smart.event_registration_status')['approved'], $input);

        $data['regs'] = $list->load(['academy:id,name,is_other','member:id,lastname,firstname,profile_url', 'weight:id,weight', 'entry:id,name'])->chunk(4);
        return view($this->view_path.'.mandat_html', $data);
        /*
        $pdf = PDF::loadView($this->view_path.'.mandat_cm', $data, [], [
            'format' => 'A4-P'
        ]);

        return $pdf->stream('mandat.pdf');
        return $pdf->download('mandat.pdf');
        */
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
            
            echo "Амжилттай оноолтыг үүсгэлээ. Оноолтын хэсгээс харна уу.";
        } 
        catch (\Exception $ex)
        {
            echo "Алдаа гарлаа<br>";
            print_r($ex->getMessage());
        }
    }    

    public function bracketShow($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId)
    {
        $input = Input::all();

        $members = $this->eventRegistration->getBracketGenerationFromEvent($eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId);

        $total = count($members);

        $data['total'] = $total;
        $data['members'] = $members;
        if($total > 0)
        {
            $data['round'] = intval(log($total, 2)) + 1;
        }
        
        return view('event.bracket.generation', $data);        
    }
}
