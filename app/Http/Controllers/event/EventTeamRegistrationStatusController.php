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
use event\EventTeamRegistrationStatusRepository as EventTeamRegistrationStatus;
use event\EventTeamRegistrationRepository as EventTeamRegistration;
use reference\EventEntriesFeeRepository as EventEntriesFee;

//Models
use event\EventTeamRegistrationStatus as EventTeamRegistrationStatusModel;

use \Auth as Auth;
use Config;
use Illuminate\Support\Str;
use \Redirect as Redirect;
use Image;
use PDF;
use Carbon;

class EventTeamRegistrationStatusController extends Controller
{
    public $restful = true;

    public function __construct(EventTeamRegistrationStatus $eventTeamRegistrationStatus, EventTeamRegistration $eventTeamRegistration, EventEntriesFee $eventEntriesFee)
    {
        $this->view_path = 'event.registration';
        $this->eventTeamRegistrationStatus = $eventTeamRegistrationStatus;
        $this->eventTeamRegistration = $eventTeamRegistration;
        $this->eventEntriesFee = $eventEntriesFee;
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
        $eventTeamRegistration = $this->eventTeamRegistration->find(@$input['reg_id']);
        // dd($eventTeamRegistration);
        $nextStatuses = @Config::get('smart.event_registration_status_flow')[$eventTeamRegistration->status];
        $entryFees = $this->eventEntriesFee->getFeesByEntryId($eventTeamRegistration->entry_id);

        $data['eventTeamRegistration'] = $eventTeamRegistration;
        $data['nextStatuses'] = $nextStatuses;
        $data['entryFees'] = $entryFees;

        return view($this->view_path.'.team/form_team_status', $data);
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
        $validator = Validator::make($input, eventTeamRegistrationStatusModel::$rules);

        $eventTeamRegistration = $this->eventTeamRegistration->find(@$input['team_registration_id']);

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
            if($eventTeamRegistration->status != @$input['status'])
            {
                $statusArr['status'] = $input['status'];
                $statusArr['changed_by'] = Auth::id();
                $statusArr['changed_at'] = Carbon\Carbon::now()->toDateTimeString();

                try
                {
                    $eventTeamRegistration->statuses()->create($statusArr);

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
                $paymentUnq['registration_id'] = $eventTeamRegistration->id;
                
                $paymentArr['registration_id'] = $eventTeamRegistration->id;
                $paymentArr['member_id'] = $eventTeamRegistration->team_id;
                
                // $paymentArr['register_number'] = $eventTeamRegistration->member->register_number;
                $paymentArr['status'] = @$input['payment_status'] ? $input['payment_status'] : false;
                $paymentArr['amount'] = $input['amount'];

                // dd($paymentArr);
                
                try
                {
                    $eventTeamRegistration->payment()->updateOrCreate($paymentUnq, $paymentArr);

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
