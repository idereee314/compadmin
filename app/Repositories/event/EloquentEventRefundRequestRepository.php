<?php namespace event;

use event\EventRefundRequest;
use event\EventPayment;

use Hash;
use Log;
use ConfigHelper;
use DateHelper;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;

use SecurityHelper;
use Carbon;
use Session;
use Config;

class EloquentEventRefundRequestRepository implements EventRefundRequestRepository {

	public function all()
	{
		return EventRefundRequest::all();
	}

	public function allPaginate()
	{
		return EventRefundRequest::paginate(ConfigHelper::getConfigValueByCode('pagination_global_list'));
	}

	public function find($id)
	{
		return EventRefundRequest::find($id);
	}

	public function create($input)
	{
		$eventRefundRequest = new EventRefundRequest;
		$eventRefundRequest->member_id = $input['athlete_id'];
		$eventRefundRequest->event_id = $input['event_id'];
		$eventRefundRequest->academy_id = @$input['academy_id'];
		$eventRefundRequest->amount = @$input['amount'];
		$eventRefundRequest->description = @$input['description'];
		
		$eventRefundRequest->save();
		return $eventRefundRequest;
	}

 	public function update($id, $input)
	{
		$eventRefundRequest = $this->find($id);
		$eventRefundRequest->event_id = $input['event_id'];
		$eventRefundRequest->member_id = @$input['athlete_id'];
		$eventRefundRequest->academy_id = @$input['academy_id'];
		$eventRefundRequest->status = @$input['status'];
		$eventRefundRequest->amount = @$input['amount'];
		$eventRefundRequest->description = @$input['description'];
		
		$eventRefundRequest->save();
		return $eventRefundRequest;
	}

	public function delete($id)
	{
		$eventRegistraion = $this->find($id);

		$eventRegistraion->delete();
	}

	public function getEventRegStatusCount($eventId)
	{
		$count = "";
		if(@$eventId)
		{
			$qry = EventRefundRequest::selectRaw('status, count(*) as total')->where('event_id', $eventId)->groupBy('status');
			$count = $qry->get();
		}

		return $count;
	}

	public function getPaymentByEventId($eventId)
	{
		$fees = "";
		if(@$eventId)
		{
			$qry = EventRegistration::selectRaw('distinct on (uq_event_registration.id) uq_event_registration.id, uq_event_entries_fee.entrance_fee, COALESCE(amount, amount, 0) as amount, round(uq_event_payment.amount*0.99) as fee_amount')
				->leftJoin('uq_event_payment', function($join){
					$join->on('uq_event_registration.id', '=', 'uq_event_payment.registration_id')
						->where('uq_event_payment.status', true);
				})
				->join('uq_event_entries_fee', 'uq_event_registration.entry_id', '=', 'uq_event_entries_fee.entry_id')
				->where('uq_event_registration.event_id', $eventId)
				->where('uq_event_registration.status', @Config::get('smart.event_registration_status')['approved']);
			$fees = $qry->get()->sortBy('amount');
		}

		return $fees;
	}

	public function getRegisteredEvent($eventId)
	{
		return DB::select("select * from uq_comp.uq_event_registration uer 
		left join uq_comp.uq_member um on um.id = uer.member_id 
		where uer.event_id = $eventId and uer.status = 'approved'
		");
	}
	
}
