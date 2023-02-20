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
use event\EventRegistrationStatusRepository as EventRegistrationStatus;
use event\EventRegistrationRepository as EventRegistration;
use reference\EventEntriesFeeRepository as EventEntriesFee;
use event\EventTeamRegistrationRepository as EventTeamRegistration;

//Models
use event\EventRegistrationStatus as EventRegistrationStatusModel;

use \Auth as Auth;
use Config;
use Illuminate\Support\Str;
use \Redirect as Redirect;
use Image;
use PDF;
use Carbon;

class EventRegistrationStatusController extends Controller
{
    public $restful = true;

    public function __construct(EventRegistrationStatus $eventRegistrationStatus, EventRegistration $eventRegistration, EventEntriesFee $eventEntriesFee, EventTeamRegistration $eventTeamRegistration)
    {
        $this->view_path = 'event.registration';
        $this->eventRegistrationStatus = $eventRegistrationStatus;
        $this->eventRegistration = $eventRegistration;
        $this->eventEntriesFee = $eventEntriesFee;
        $this->eventTeamRegistration = $eventTeamRegistration;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function change()
    {
        $input = Input::all();
        // dd($this->eventTeamRegistration->find(@$input['reg_id']));
        $eventRegistration = $this->eventRegistration->find(@$input['reg_id']);
        $eventTeamRegistration = $this->eventTeamRegistration->find(@$input['reg_id']);
        $nextStatuses = @Config::get('smart.event_registration_status_flow')[$eventRegistration->status];
        // dd($this->eventEntriesFee->getFeesByEntryId($eventTeamRegistration->entry_id));
        // dd($this->eventEntriesFee->getFeesByEntryId($eventTeamRegistration->entry_id));
        $entryFees = $this->eventEntriesFee->getFeesByEntryId($eventRegistration->entry_id);

        $data['eventRegistration'] = $eventRegistration;
        $data['nextStatuses'] = $nextStatuses;
        $data['entryFees'] = $entryFees;

        return view($this->view_path.'.form_status', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changed(Request $request)
    {
        $input = Input::all();
        $validator = Validator::make($input, EventRegistrationStatusModel::$rules);
        $eventRegistration = $this->eventRegistration->find(@$input['event_registration_id']);

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
            if($eventRegistration->status != @$input['status'])
            {
                $statusArr['status'] = $input['status'];
                $statusArr['changed_by'] = Auth::id();
                $statusArr['changed_at'] = Carbon\Carbon::now()->toDateTimeString();

                try
                {
                    $eventRegistration->statuses()->create($statusArr);

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

            if(array_key_exists('amount', $input))
            {
                $paymentUnq['registration_id'] = $eventRegistration->id;

                $paymentArr['registration_id'] = $eventRegistration->id;
                $paymentArr['member_id'] = $eventRegistration->member_id;
                $paymentArr['register_number'] = $eventRegistration->member->register_number;
                $paymentArr['status'] = @$input['payment_status'] ? $input['payment_status'] : false;
                $paymentArr['amount'] = $input['amount'];
                
                try
                {
                    $eventRegistration->payment()->updateOrCreate($paymentUnq, $paymentArr);

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
}
